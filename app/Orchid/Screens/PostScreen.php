<?php

namespace App\Orchid\Screens;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\CreateImageRequest;
use App\Http\Requests\CreatePostRequest;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Orchid\Layouts\PostLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
class PostScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            "posts" => Post::paginate(5),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "Posts";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make(
                "Create Post ( Choose An Alternative Option Besides Post Editor )"
            )
                ->icon("plus")
                ->method("createPost")
                ->modal("createPost"),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            PostLayout::class,
            $this->createPostModal(),
            $this->imageUploadSection(),
            $this->editPostModal(),
            $this->createCommentModal(),
        ];
    }

    public function asyncGetPost(Post $post)
    {
        return [
            "content" => $post->content,
            "title" => $post->title,
        ];
    }
    public function asyncGetComment()
    {
        return [
                ////
            ];
    }
    public function createComment(CreateCommentRequest $request, Post $post)
    {
        Comment::create([
            "post_id" => $post->id,
            "user_id" => Auth::user()->id,
            "text" => $request->text,
        ]);
        Alert::success("You Created A Comment");
    }
    public function updatePost(CreatePostRequest $request, Post $post): void
    {
        $post->update([
            "content" => $request->content,
            "title" => $request->title,
        ]);
        Alert::info("You Changed The Post");
    }
    public function deletePost(int $postId): void
    {
        $post = Post::find($postId);
        if (Auth::user()->id === $post->user_id) {
            $post->delete();
        }
        Alert::error("You Deleted A Post");
    }

    public function createPost(CreatePostRequest $request)
    {
        Post::create([
            "title" => $request->title,
            "user_id" => Auth::user()->id,
            "content" => $request->content,
        ]);
        Alert::success("You Created A Post");
    }
    public function createImage(CreateImageRequest $request)
    {
        $filePath = $request->file("path")->store("uploads", "public");
        Image::create([
            "path" => $filePath,
        ]);
        Alert::success("You Created A Image");
    }
    protected function createPostModal()
    {
        return Layout::modal("createPost", [
            Layout::rows([
                Input::make("title")
                    ->title("Title")
                    ->placeholder("Create Title"),
            ]),
            Layout::rows([
                Input::make("content")
                    ->title("Content")
                    ->placeholder("Create Content"),
            ]),
        ])
            ->title("Create Post")
            ->applyButton("Create");
    }
    protected function imageUploadSection()
    {
        return Layout::rows([
            Input::make("title")->title("Post Title"),
            Quill::make("content")->title("Post Editor"),
            Input::make("path")->type("file"),
            Button::make("Create Image")->method("createImage"),
            Button::make("Create Post")->method("createPost"),
        ]);
    }
    protected function editPostModal()
    {
        return Layout::modal(
            "editPost",
            Layout::rows([
                Input::make("content")->placeholder("Editing Content"),
                Input::make("title")->placeholder("Editing Title"),
            ])
        )->async("asyncGetPost");
    }
    protected function createCommentModal()
    {
        return Layout::modal(
            "createComment",
            Layout::rows([Input::make("text")->placeholder("Comment Creating")])
        )->async("asyncGetComment");
    }
}
