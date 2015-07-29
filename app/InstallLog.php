<?php
namespace App;

# general
use Illuminate\Database\Eloquent\Model;

class InstallLog extends Model
{
    protected $table = 'install_log';

    protected $fillable = ['app_id', 'package_id', 'mail', 'user_agent', 'installed'];

}