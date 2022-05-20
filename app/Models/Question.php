<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ["text","order"];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function quizzes()
    {
        return $this->belongsToMany(Question::class)->withTimestamps();
    }

    public function scopeChangeOrder($query, array $items)
    {
        $iDs = $this->getIDs($items);
        $statement = $this->createStatement($items, $iDs);
        DB::statement($statement);
    }

    private function getIDs(array $items): string
    {
        $items_id =   array_map(function($item){
            return $item['value'];
        }, $items);
       return implode(',', $items_id);
    }

    private function createStatement(array $items, string $iDs): string
    {
        $statement = "UPDATE `questions` SET `order` = CASE";

        foreach($items as $item){
            $statement .= " WHEN `id` = ". $item['value'] ." THEN ". $item['order'];
        }
        $statement .= " ELSE `order` END WHERE id in(".$iDs. ")";

        return $statement;
    }
}
