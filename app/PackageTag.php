<?php
namespace App;

# general
use Illuminate\Database\Eloquent\Model;

class PackageTag extends Model
{
    protected $table = 'package_tag';

    protected $fillable = ['package_id', 'tag_id'];

}