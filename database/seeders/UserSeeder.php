<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();

        // create 1 administrateur
        $admin = User::factory()->create([
            'login_status_id' => 'VALIDATED',
            'role_id' => 'ADMIN',
        ]);

        // create some teachers
        $teachers = User::factory()->count(10)->create([
            'role_id' => 'TEACHER',
            'gender_id' => null,
            'other_comment' => null,
            'health_comment' => null
        ]);

        $groups = Group::all();

        foreach ($groups as $group) {
            // a father
            $firstName = str_replace(' ', '', $this->stripAccents(fake()->firstName('male')));
            $email = strtolower($firstName) . '.' . strtolower($group->name) . '@' . fake()->domainName();
            $address = fake()->streetAddress();
            $city = fake()->city();
            $postalCode = fake()->postcode();
            $countryId = 'FR';

            $father = User::factory()->create([
                'role_id' => 'PARENT',
                'last_name' => $group->name,
                'first_name' => $firstName,
                'email' => $email,
                'civility_id' => 'MR',
                'gender_id' => null,
                'address' => $address,
                'city' => $city,
                'postal_code' => $postalCode,
                'country_id' => $countryId,
                'health_comment' => null,
            ]);

            $this->createUserGroup($group, $father);

            // a mother
            $firstName = str_replace(' ', '', $this->stripAccents(fake()->firstName('female')));
            $email = strtolower($firstName) . '.' . strtolower($group->name) . '@' . fake()->domainName();
            $mother = User::factory()->create([
                'role_id' => 'PARENT',
                'last_name' => $group->name,
                'first_name' => $firstName,
                'email' => $email,
                'gender_id' => null,
                'civility_id' => 'MISS',
                'gender_id' => null,
                'address' => $address,
                'city' => $city,
                'postal_code' => $postalCode,
                'country_id' => $countryId,
                'health_comment' => null,
            ]);

            $this->createUserGroup($group, $mother);

            // X students linked to the same group
            for ($i = 0; $i < rand(1, 4); $i++) {

              

                $genderId = fake()->randomElement(['1', '2']);
                $firstName = str_replace(' ', '', $this->stripAccents(fake()->firstName($genderId == '1' ? 'male' : 'female')));
                $email = strtolower($firstName) . '.' . strtolower($group->name) . '@' . fake()->domainName();
                $student = User::factory()->create([
                    'role_id' => 'STUDENT',
                    'gender_id' => $genderId,
                    'last_name' => $group->name,
                    'first_name' => $firstName,
                    // 'birth_date' => fake()->date('d/m/Y'),
                    'birth_date' => fake()->dateTimeInInterval('-15 years', '+3 years')->format('d/m/Y'),
                    'email' => $email,
                    'civility_id' => null,
                    'address' => $address,
                    'city' => $city,
                    'postal_code' => $postalCode,
                    'country_id' => $countryId
                ]);

                $this->createUserGroup($group, $student);
            }
        }
    }

    /**
     * @param  Collection<User>  $users
     */
    public function createUserGroup(Group $group, User $user): void
    {
        UserGroup::factory()->create([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
    }

    public function stripAccents(string $str): string
    {
        // return strtr($str, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $accents = ['À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'Ā' => 'A', 'ā' => 'a', 'Ă' => 'A', 'ă' => 'a', 'Ą' => 'A', 'ą' => 'a', 'Ç' => 'C', 'ç' => 'c', 'Ć' => 'C', 'ć' => 'c', 'Ĉ' => 'C', 'ĉ' => 'c', 'Ċ' => 'C', 'ċ' => 'c', 'Č' => 'C', 'č' => 'c', 'Ð' => 'D', 'ð' => 'd', 'Ď' => 'D', 'ď' => 'd', 'Đ' => 'D', 'đ' => 'd', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'Ē' => 'E', 'ē' => 'e', 'Ĕ' => 'E', 'ĕ' => 'e', 'Ė' => 'E', 'ė' => 'e', 'Ę' => 'E', 'ę' => 'e', 'Ě' => 'E', 'ě' => 'e', 'Ĝ' => 'G', 'ĝ' => 'g', 'Ğ' => 'G', 'ğ' => 'g', 'Ġ' => 'G', 'ġ' => 'g', 'Ģ' => 'G', 'ģ' => 'g', 'Ĥ' => 'H', 'ĥ' => 'h', 'Ħ' => 'H', 'ħ' => 'h', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'Ĩ' => 'I', 'ĩ' => 'i', 'Ī' => 'I', 'ī' => 'i', 'Ĭ' => 'I', 'ĭ' => 'i', 'Į' => 'I', 'į' => 'i', 'İ' => 'I', 'ı' => 'i', 'Ĵ' => 'J', 'ĵ' => 'j', 'Ķ' => 'K', 'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'L', 'ĺ' => 'l', 'Ļ' => 'L', 'ļ' => 'l', 'Ľ' => 'L', 'ľ' => 'l', 'Ŀ' => 'L', 'ŀ' => 'l', 'Ł' => 'L', 'ł' => 'l', 'Ñ' => 'N', 'ñ' => 'n', 'Ń' => 'N', 'ń' => 'n', 'Ņ' => 'N', 'ņ' => 'n', 'Ň' => 'N', 'ň' => 'n', 'ŉ' => 'n', 'Ŋ' => 'N', 'ŋ' => 'n', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'Ō' => 'O', 'ō' => 'o', 'Ŏ' => 'O', 'ŏ' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Ŕ' => 'R', 'ŕ' => 'r', 'Ŗ' => 'R', 'ŗ' => 'r', 'Ř' => 'R', 'ř' => 'r', 'Ś' => 'S', 'ś' => 's', 'Ŝ' => 'S', 'ŝ' => 's', 'Ş' => 'S', 'ş' => 's', 'Š' => 'S', 'š' => 's', 'ſ' => 's', 'Ţ' => 'T', 'ţ' => 't', 'Ť' => 'T', 'ť' => 't', 'Ŧ' => 'T', 'ŧ' => 't', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'Ũ' => 'U', 'ũ' => 'u', 'Ū' => 'U', 'ū' => 'u', 'Ŭ' => 'U', 'ŭ' => 'u', 'Ů' => 'U', 'ů' => 'u', 'Ű' => 'U', 'ű' => 'u', 'Ų' => 'U', 'ų' => 'u', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ý' => 'Y', 'ý' => 'y', 'ÿ' => 'y', 'Ŷ' => 'Y', 'ŷ' => 'y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'ź' => 'z', 'Ż' => 'Z', 'ż' => 'z', 'Ž' => 'Z', 'ž' => 'z'];

        return strtr($str, $accents);
    }
}
