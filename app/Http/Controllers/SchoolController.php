<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function suggest($property)
    {
        $q = request('q');

        $res = collect([]);

        if ($property === 'name') {
            $res = collect([
                'Paragon International University',
            ]);
        } else if ($property === 'role') {
            $res = collect([
                'Dean',
                'Head of Department',
                'Instructor',
                'Provost',
                'Rector',
            ]);
        }

        if (!$q) return $res->toArray();

        $res = $res->filter(function ($value) use ($q) {
            return str_starts_with(Str::lower($value), Str::lower($q)) && Str::lower($value) !== Str::lower($q);
        });

        $like = config('database.default') === 'pgsql' ? 'ILIKE' : 'LIKE';
        $args = ["$q%", "$q"];

        if ($property === 'name') {
            $_res = School::whereRaw("name $like ? and name != ?", $args)->take(5)->pluck('name');
            $res->push(...$_res);

            return $res->unique()->toArray();
        } else if ($property === 'role') {
            $_res = School::whereRaw("user_role_in_school $like ? and user_role_in_school != ?", $args)->take(5)->pluck('name');
            $res->push(...$_res);

            return $res->unique()->toArray();
        } else {
            return [];
        }
    }
}
