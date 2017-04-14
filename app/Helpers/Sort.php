<?php

function sort_by_two($array, $col1, $col2)
{
    $sorted = array_sort($array, function ($value) use ($col1, $col2) {
        return sprintf('%s,%s', $value[$col1], $value[$col2]);
    });

    return $sorted;
}

function sort_by($column, $body, $request)
{


    $sortBy = Session::get('sortBy');

    if ($request->has('sortBy')) {
        $sortBy = $request->get('sortBy');
        Session::put('sortBy', $sortBy);
    }

    Session::get('order') == 'asc' ? $direction = 'desc' : $direction = 'asc';


    if ($request->has('order')) {
        $request->get('order') == 'asc' ? $direction = 'desc' : $direction = 'asc';
        Session::put('order', $direction);
    }
    if ($column == $sortBy) {
        $sort_icon = asset('images/sort_' . $direction . '_icon.png');
    } else {
        $sort_icon = asset('images/sort_icon.png');
    }

    $object = URL::current();
    if ($column == $sortBy) {

    }
    $html = '<a href="' . $object . '?sortBy=' . $column . '&order=' . $direction . '">' . $body . ' <img class="sort-icon" src="' . $sort_icon . '" /></a>';


    return $html;
}