<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\DB;

class AppInstallUser extends Model {

    use Authenticatable, CanResetPassword;

    protected $table = 'app_install_user';
    protected $fillable = [
        'app_id',
        'mail',
        'notify',
        'last_installed',
        'created_at',
        'updated_at',
    ];

    public static function findOrNewByMail($mail, $data)
    {
        $new = [
            'app_id' => $data['app_id'],
            'mail' => $data['mail'],
            'last_installed' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $user = DB::table('user_pass')
                    ->where('mail', $mail)
                    ->first();
        $find = AppInstallUser::find($user->id);

        return $find ? $find : new AppInstallUser($new);
    }
}
