<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        "text",
        "correct",
        "question_id",
        "order"
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeChangeOrder($query, array $items): Collection
    {
        $iDs = $this->getIDs($items);
        $statement = $this->createStatement($items, $iDs);
        DB::statement($statement);
        return  $this->getAnswersByIds($items);

    }

    private function getAnswersByIds( $items )
    {
        $answersIDs = array_map(function($item){
            return $item['value'];
        },$items);

        return Answer::whereIn('id',$answersIDs)->orderBy('order')->orderByDesc('updated_at')->get();

    }

    public function getIDs(array $items): string
    {
        $items_id =   array_map(function($item){
            return $item['value'];
        }, $items);
        return implode(',', $items_id);
    }

    private function createStatement(array $items, string $iDs): string
    {
        $statement = "UPDATE `answers` SET `order` = CASE";

        foreach($items as $item){
            $statement .= " WHEN `id` = ". $item['value'] ." THEN ". $item['order'];
        }
        $statement .= " ELSE `order` END WHERE id in(".$iDs. ")";

        return $statement;
    }
}
