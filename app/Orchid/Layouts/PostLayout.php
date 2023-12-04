<?php

namespace App\Orchid\Layouts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PostLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = "posts";

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make("id"),
            TD::make("title")->render(function ($post) {
                return $post->title ?? "----";
            }),
            TD::make("Content")->render(function ($post) {
                return $post->content ?? "----";
            }),
            TD::make("Created")->render(function ($post) {
                return $post->created_at ?? "----";
            }),
            TD::make("Updated")->render(function ($post) {
                return $post->updated_at ?? "----";
            }),

            TD::make("Create Comment")->render(function (Post $post) {
                return ModalToggle::make("")
                    ->icon("plus")
                    ->modal("createComment")
                    ->method("createComment")
                    ->modalTitle("Comment Creating")
                    ->asyncParameters([
                        "post" => $post->id,
                    ]);
            }),
            TD::make("Comments Count")->render(function (Post $post) {
                return $post->comments->count() === 0
                    ? "no comments yet"
                    : $post->comments->count();
            }),
            TD::make("Edit")->render(function (Post $post) {
                return Auth::user()->id === $post->user_id
                    ? ModalToggle::make("")
                        ->icon("pencil")
                        ->modal("editPost")
                        ->method("updatePost")
                        ->modalTitle("Post Editing")
                        ->asyncParameters([
                            "post" => $post->id,
                        ])
                    : "You Have No Rights";
            }),

            TD::make("Delete")->render(function ($post) {
                return Auth::user()->id === $post->user_id
                    ? Button::make("")
                        ->icon("trash")
                        ->confirm(
                            "After deleting, the category will be gone forever."
                        )
                        ->method("deletePost")
                        ->parameters(["postId" => $post->id])
                    : "You Have No Rights";
            }),
        ];
    }
}
