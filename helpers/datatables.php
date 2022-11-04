<?php

function createTables($request = null, $components = null)
{
    if (!$request)
        return setResponse(400);

    if (!$components)
        return setResponse(400);

    $model = $components['model'];
    $func = $components['func'];
    $condition = (isset($components['condition'])) ? $components['condition'] : null;

    $numbcol = $request->get('order');
    $columns = $request->get('columns');

    $echo = $request->get('draw');
    $start = $request->get('start');
    $perpage = $request->get('length');

    $search = $request->get('search');
    $search = $search['value'];
    $pattern = '/[^a-zA-Z0-9 !@#$%^&*\/\.\,\(\)-_:;?\+=]/u';
    $search = preg_replace($pattern, '', $search);

    $sort = $numbcol[0]['dir'];
    $field = $columns[$numbcol[0]['column']]['data'];

    $page = ($start / $perpage) + 1;

    if ($page >= 0) {
        $result = $model->$func($start, $perpage, $search, false, $sort, $field, $condition);
        $total = $model->$func($start, $perpage, $search, true, $sort, $field, $condition);
    } else {
        $result = $model::orderBy($field, $sort)->get();
        $total = $model::all()->count();
    }

    return response()->json([
        'sEcho' => $echo,
        'iTotalRecords' => $total,
        'iTotalDisplayRecords' => $total,
        'aaData' => $result
    ], 200);
}
