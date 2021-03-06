<?php

namespace App;

use App\Org;
use App\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\App;
use function GuzzleHttp\json_decode;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    /**
     * Free to fill
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 定义josn列
     *
     */
    protected $casts = [
        'ids' => 'json',
        'info' => 'json',
        'conf' => 'json',
    ];

    /**
     * Parent
     *
     */
    public function parent()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Children
     *
     */
    public function children()
    {
        return $this->hasMany('App\User', 'created_by');
    }

    /**
     * orders
     *
     */
    public function orders()
    {
        return $this->hasMany('App\Order', 'openid', 'ids->wechat->id');
    }

    /**
     * Tickets
     *
     */
    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'user_id');
    }



    /**
     * Orgs of a user
     *
     */
    public function orgs()
    {
        return $this->belongsToJson('App\Org', 'conf->roles[]->org_id');
    }

    /**
     * Roles of a user
     *
     */
    public function roles()
    {
        return $this->belongsToJson('App\Role', 'conf->roles[]->role_id');
    }

    /**
     *  查询修改器
     *
     * @param  string  $value
     * @return string
     */
    public function getConfAttribute($value)
    {
        if(is_string($value)) {
            $value = json_decode($value, true);
        }elseif(is_array($value)) {
            // $array = $json;
        }else{
            return $value;
        };

        if(!Arr::has($value, 'roles') || !is_array($value['roles']) || !count($value['roles'])) return $value;

        for ($i=0; $i < count($value['roles']); $i++) {
            $value['roles'][$i]['org'] = Org::find( $value['roles'][$i]['org_id']);
            $value['roles'][$i]['role'] = Role::withDepth()->find( $value['roles'][$i]['role_id']);
        }

        return $value;
    }

    /**
     *  查询修改器
     *
     * @param  string  $value
     * @return string
     */
    public function getInfoAttribute($value)
    {
        if(is_string($value)) {
            $value = json_decode($value, true);
        }elseif(is_array($value)) {
            // $array = $json;
        }else{
            return $value;
        };

        if(!Arr::has($value, 'nick')) return $value;

        $value['nick'] = Str::title($value['nick']);

        return $value;
    }

    /**
     * Tickets
     *
     */
    public function finance_to()
    {
        return $this->hasMany('App\Finance', 'to->id');
    }

    /**
     * Finances
     *
     */
    public function finance_from()
    {
        return $this->hasMany('App\Finance', 'from->id');
    }

    /**
     * Roles of a user
     *
     */
    public function tasks()
    {
        return $this->hasManyJson('App\Task', 'parts[]->id');
    }

    /**
     * Finances
     *
     */
    public function tasks_create()
    {
        return $this->hasMany('App\Task', 'created_by');
    }

    /**
     * 视频
     *
     */
    public function videos()
    {
        return $this->hasMany('App\Video', 'created_by');
    }

    /**
     * 积分
     *
     */
    public function scores()
    {
        return $this->hasMany('App\Score', 'user_id');
    }

    /**
     * 视频
     *
     */
    public function favorites_video()
    {
        return $this->belongsToJson('App\Video', 'info->favorites->video[]->id');
    }
    
}
