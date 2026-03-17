<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Note;

class NoteController extends Controller
{
//    public function index()
//    {
//        $notes = DB::table('notes')
//            ->whereNull('deleted_at')
//            ->orderBy('updated_at', 'desc')
//            ->get();
//        return response()->json(['notes' => $notes], Response::HTTP_OK);
//    }
    public function index()
    {
        $notes = Note::query()
            ->orderByDesc('updated_at')
            ->get();

        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }

//    public function store(Request $request)
//    {
//        $data = $request->validate([
//            'user_id' => 'required|integer',
//            'title' => 'required|string|max:128',
//            'body' => 'nullable|string',
//        ]);
//
//        DB::table('notes')->insert([
//            'user_id'    => $data['user_id'],
//            'title'      => $data['title'],
//            'body'       => $data['body'] ?? null,
//            'status'     => 'draft',
//            'is_pinned'  => false,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
//
//        return response()->json([
//            'message' => 'Poznámka bola úspešne vytvorená.'
//        ], Response::HTTP_OK);
//    }
    public function store(Request $request)
    {
        $note = Note::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json([
            'message' => 'Poznámka bola úspešne vytvorená.',
            'note' => $note,
        ], Response::HTTP_CREATED);
    }

//    public function show(string $id)
//    {
//        $note = DB::table('notes')
//            ->whereNull('deleted_at')
//            ->where('id', $id)
//            ->first();
//
//        if (!$note) {
//            return response()->json([
//                'message' => 'Poznámka nenájdená.'
//            ], Response::HTTP_NOT_FOUND);
//        }
//
//        return response()->json([
//            'note' => $note
//        ], Response::HTTP_OK);
//    }
    public function show(string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['note' => $note], Response::HTTP_OK);
    }

//    public function update(Request $request, string $id)
//    {
//        $note = DB::table('notes')->where('id', $id)->first();
//        if (!$note) {
//            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
//        }
//
//        $data = $request->validate([
//            'title' => 'required|string|max:128',
//            'body'  => 'nullable|string',
//        ]);
//
//        DB::table('notes')->where('id', $id)->update([
//            'title'      => $data['title'],
//            'body'       => $data['body'] ?? null,
//            'updated_at' => now(),
//        ]);
//
//        return response()->json([
//            'message' => 'Poznámka bola úspešne aktualizovaná.'
//        ], Response::HTTP_OK);
//    }
    public function update(Request $request, string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $note->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json(['note' => $note], Response::HTTP_OK);
    }

//    public function destroy(string $id) // toto je soft delete
//    {
//        $note = DB::table('notes')
//            ->whereNull('deleted_at')
//            ->where('id', $id)
//            ->first();
//
//        if (!$note) {
//            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
//        }
//
//        DB::table('notes')->where('id', $id)->update([
//            'deleted_at' => now(),
//            'updated_at' => now(),
//        ]);
//
////        DB::table('notes')->where('id', $id)->delete();
//
//        return response()->json(['message' => 'Poznámka bola úspešne odstránená.'], Response::HTTP_OK);
//    }
    public function destroy(string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $note->delete(); // soft delete

        return response()->json(['message' => 'Poznámka bola úspešne odstránená.'], Response::HTTP_OK);
    }

//    VERSION 1.0
//    public function statsByStatus()
//    {
//        $stats = DB::table('notes')
//            ->select('status', DB::raw('COUNT(*) as count'))
//            ->groupBy('status')
//            ->get();
//
//        return response()->json([
//            'stats' => $stats
//        ]);
//    }

//    VERSION 2.0
//    public function statsByStatus()
//    {
//        $stats = DB::table('notes')
//            ->whereNull('deleted_at')
//            ->select('status', DB::raw('COUNT(*) as count'))
//            ->groupBy('status')
//            ->orderBy('status')
//            ->get();
//
//        return response()->json(['stats' => $stats], Response::HTTP_OK);
//    }

    public function statsByStatus()
    {
        $stats = Note::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        return response()->json(['stats' => $stats], Response::HTTP_OK);
    }

//    VERSION 1.0
//    public function archiveOldDrafts()
//    {
//        $affected = DB::table('notes')
//            ->where('status', 'draft')
//            ->where('updated_at', '<', now()->subDays(30))
//            ->update([
//                'status' => 'archived',
//                'updated_at' => now(),
//            ]);
//
//        return response()->json([
//            'message' => 'Staré koncepty boli archivované.',
//            'affected_rows' => $affected
//        ]);
//    }
//
//    VERSION 2.0
//    public function archiveOldDrafts()
//    {
//        $affected = DB::table('notes')
//            ->whereNull('deleted_at')
//            ->where('status', 'draft')
//            ->where('updated_at', '<', now()->subDays(30))
//            ->update([
//                'status' => 'archived',
//                'updated_at' => now(),
//            ]);
//
//        return response()->json([
//            'message' => 'Staré koncepty boli archivované.',
//            'affected_rows' => $affected,
//        ]);
//    }
    public function archiveOldDrafts()
    {
        $affected = Note::archiveOldDrafts();

        return response()->json([
            'message' => 'Staré koncepty boli archivované.',
            'affected_rows' => $affected,
        ], Response::HTTP_OK);
    }


//    VERSION 1.0
//    public function userNotesWithCategories(string $userId)
//    {
//        $notes = DB::table('notes')
//            ->join('note_category', 'notes.id', '=', 'note_category.note_id')
//            ->join('categories', 'note_category.category_id', '=', 'categories.id')
//            ->where('notes.user_id', $userId)
//            ->orderBy('notes.updated_at', 'desc')
//            ->select('notes.id', 'notes.title', 'categories.name as category')
//            ->get();
//
//        return response()->json([
//            'notes' => $notes
//        ]);
//    }
//    VERSION 2.0
//    public function userNotesWithCategories(string $userId)
//    {
//        $rows = DB::table('notes')
//            ->join('note_category', 'notes.id', '=', 'note_category.note_id')
//            ->join('categories', 'note_category.category_id', '=', 'categories.id')
//            ->where('notes.user_id', $userId)
//            ->whereNull('notes.deleted_at')
//            ->orderBy('notes.updated_at', 'desc')
//            ->select('notes.id', 'notes.title', 'categories.name as category')
//            ->get();
//
//        return response()->json(['notes' => $rows], Response::HTTP_OK);
//    }
    public function userNotesWithCategories(string $userId)
    {
        $notes = Note::with('categories')
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get();

        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }

//    public function search(Request $request)
//    {
//        $q = trim((string) $request->query('q', ''));
//
//        $notes = DB::table('notes')
//            ->whereNull('deleted_at')
//            ->where('status', 'published')
//            ->where(function ($x) use ($q) {
//                $x->where('title', 'like', "%{$q}%")
//                    ->orWhere('body', 'like', "%{$q}%");
//            })
//            ->orderBy('updated_at', 'desc')
//            ->limit(20)
//            ->get();
//
//        return response()->json([
//            'query' => $q,
//            'notes' => $notes,
//        ], Response::HTTP_OK);
//    }
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $notes = Note::searchPublished($q);

        return response()->json(['query' => $q, 'notes' => $notes], Response::HTTP_OK);
    }

//    public function pinNote(string $id)
//    {
//        DB::table('notes')
//            ->where('id', $id)
//            ->update([
//                'is_pinned' => 1
//            ]);
//
//        return response()->json([
//            'message' => 'Note pinned'
//        ]);
//    }
    public function pinNote(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note->pin(); // 👈 ВАЖНО
        return response()->json([
            'message' => 'Note pinned',
            'note' => $note
        ], Response::HTTP_OK);
    }

    public function unpinNote(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note->unpin();
        return response()->json([
            'message' => 'Note unpinned',
            'note' => $note
        ], Response::HTTP_OK);
    }

    public function publish(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note->publish();
        return response()->json([
            'message' => 'Note published',
            'note' => $note
        ], Response::HTTP_OK);
    }

    public function archive(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note->archive();
        return response()->json([
            'message' => 'Note archived',
            'note' => $note
        ], Response::HTTP_OK);
    }

}
