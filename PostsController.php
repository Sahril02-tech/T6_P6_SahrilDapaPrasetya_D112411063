<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    // 1. READ ALL (index) - Menandai Accept Header & Output JSON/XML
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $posts = Post::orderBy("id", "DESC")->get();

            if ($acceptHeader === 'application/json') {
                return response()->json($posts, 200);
            } else {
                $xml = new \SimpleXMLElement('<posts/>');
                foreach ($posts as $item) {
                    $xmlItem = $xml->addChild('post');
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('title', $item->title);
                    $xmlItem->addChild('status', $item->status);
                    $xmlItem->addChild('content', $item->content);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    // 2. CREATE (store) - Validasi Accept Header, Content-Type Header & Format Output
    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();
                $post = Post::create($input);

                if ($acceptHeader === 'application/json') {
                    return response()->json($post, 200);
                } else {
                    $xml = new \SimpleXMLElement('<post/>');
                    $xml->addChild('id', $post->id);
                    $xml->addChild('title', $post->title);
                    $xml->addChild('status', $post->status);
                    $xml->addChild('content', $post->content);
                    $xml->addChild('user_id', $post->user_id);
                    $xml->addChild('created_at', $post->created_at);
                    $xml->addChild('updated_at', $post->updated_at);

                    return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
                }
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    // 3. READ DETAIL (show) - Tantangan: Implementasi Accept Header (JSON / XML)
    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }

            if ($acceptHeader === 'application/json') {
                return response()->json($post, 200);
            } else {
                $xml = new \SimpleXMLElement('<post/>');
                $xml->addChild('id', $post->id);
                $xml->addChild('title', $post->title);
                $xml->addChild('status', $post->status);
                $xml->addChild('content', $post->content);
                $xml->addChild('user_id', $post->user_id);
                $xml->addChild('created_at', $post->created_at);
                $xml->addChild('updated_at', $post->updated_at);

                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    // 4. UPDATE (update) - Tantangan: Validasi Accept Header & Content-Type Header
    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $post = Post::find($id);
                if (!$post) {
                    return response()->json(['message' => 'Post not found'], 404);
                }

                $post->fill($request->all());
                $post->save();

                if ($acceptHeader === 'application/json') {
                    return response()->json($post, 200);
                } else {
                    $xml = new \SimpleXMLElement('<post/>');
                    $xml->addChild('id', $post->id);
                    $xml->addChild('title', $post->title);
                    $xml->addChild('status', $post->status);
                    $xml->addChild('content', $post->content);
                    $xml->addChild('user_id', $post->user_id);
                    $xml->addChild('created_at', $post->created_at);
                    $xml->addChild('updated_at', $post->updated_at);

                    return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
                }
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    // 5. DELETE (destroy) - Tantangan: Implementasi Accept Header
    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }

            $post->delete();

            if ($acceptHeader === 'application/json') {
                return response()->json(['message' => 'deleted successfully', 'post_id' => $id], 200);
            } else {
                $xml = new \SimpleXMLElement('<response/>');
                $xml->addChild('message', 'deleted successfully');
                $xml->addChild('post_id', $id);

                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }
}