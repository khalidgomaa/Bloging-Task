<?php
namespace App\Http\Controllers\Api;

use App\Services\TagService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        $result = $this->tagService->getAllTags();
        return response()->json($result, $result['status'] ? 200 : 404);
    }

    public function show($id)
    {      
        $result = $this->tagService->show($id);
        return response()->json($result, $result['status'] ? 200 : 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tags|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->tagService->createTag($request->only('name'));
        return response()->json($result, $result['status'] ? 201 : 500);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tags,name,' . $id . '|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->tagService->updateTag($id, $request->only('name'));
        return response()->json($result, $result['status'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $result = $this->tagService->deleteTag($id);
        
        return response()->json($result, status: $result['status'] ? 200 : 404);
    }
}
