<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DictType;
use App\Models\DictValue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');
        $pager = DictType::query()
            ->filter($request->all())
            ->orderByDesc('id')->paginate($perPage);

        return Response::ok([
            'items' => $pager->items(),
            'total' => $pager->total(),
        ]);
    }

    /**
     * show user detail
     *
     * @param Request $request
     * @param DictType $item
     * @return void
     */
    public function show(Request $request, DictType $item)
    {
        return Response::ok($item);
    }

    public function store(Request $request)
    {
        $formData = $request->only(['name', 'alias']);
        $item = new DictType($formData);
        $ok = $item->save();
        return $ok?Response::ok():Response::fail('保存出错');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DictType $item)
    {
        $formData = $request->only(['name', 'alias']);
        $ok = $item->update($formData);
        return $ok?Response::ok():Response::fail('保存出错');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DictType $item)
    {
        $ok = $item->delete();
        return $ok?Response::ok():Response::fail('删除出错');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function valueIndex(Request $request)
    {
        $perPage = $request->get('perPage');
        $pager = DictValue::query()
            ->filter($request->all())
            ->orderBy('sort_order')
            ->orderBy('id')->paginate($perPage);

        return Response::ok([
            'items' => $pager->items(),
            'total' => $pager->total(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function valueList(Request $request, string $dictAlias)
    {
        $dict = DictType::query()->where('alias', $dictAlias)->first();
        $values = DictValue::query()->where('dict_id', $dict->id)->get();

        return Response::ok(\App\Http\Resources\Admin\DictValue::collection($values));
    }

    /**
     * show user detail
     *
     * @param Request $request
     * @param DictType $item
     * @return void
     */
    public function valueShow(Request $request, DictValue $item)
    {
        return Response::ok($item);
    }

    public function valueStore(Request $request)
    {
        $formData = $request->only(['dict_id', 'dict_value', 'dict_label', 'sort_order']);
        $item = new DictValue($formData);
        $ok = $item->save();
        return $ok?Response::ok():Response::fail('保存出错');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function valueUpdate(Request $request, DictValue $item)
    {
        $formData = $request->only(['dict_id', 'dict_value', 'dict_label', 'sort_order']);
        $ok = $item->update($formData);
        return $ok?Response::ok():Response::fail('保存出错');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function valueDestroy(DictValue $item)
    {
        $ok = $item->delete();
        return $ok?Response::ok():Response::fail('删除出错');
    }
}
