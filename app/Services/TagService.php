<?php
namespace App\Services;

use App\Models\Tag;
use App\Repositories\Tag\TagRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags()
    {
        $tags = $this->tagRepository->getAll();
        return [
            'status' => true,
            'data' => $tags
        ];
    }

    public function show($id)
    {
        try {
            $tag = $this->tagRepository->findById($id);
            return [
                'status' => true,
                'data' => $tag
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'status' => false,
                'message' => 'Tag not found.'
            ];
        }
    }

    public function createTag(array $data)
    {
        $tag = $this->tagRepository->create($data);
        return [
            'status' => true,
            'data' => $tag,
             'message' => 'Tag createted successfully.'
        ];
    }

    public function updateTag($id, array $data)
    {
        try {
            $tag = $this->tagRepository->update($id, $data);
            return [
                'status' => true,
                'data' => $tag,
                   'message' => 'Tag updated successfully.'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'status' => false,
                'message' => 'Tag not found.'
            ];
        }
    }

    public function deleteTag($id)
    {
        try {
            $this->tagRepository->delete($id);
            return [
                'status' => true,
                'message' => 'Tag deleted successfully.'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'status' => false,
                'message' => 'Tag not found.'
            ];
        }
    }
}