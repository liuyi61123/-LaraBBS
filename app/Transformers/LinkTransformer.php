<?php
/**
 * Created by PhpStorm.
 * User: player
 * Date: 2018/2/7
 * Time: 17:34
 */

namespace App\Transformers;

use App\Models\Link;
use League\Fractal\TransformerAbstract;

class LinkTransformer extends TransformerAbstract
{
    public function transform(Link $link)
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link,
        ];
    }
}
