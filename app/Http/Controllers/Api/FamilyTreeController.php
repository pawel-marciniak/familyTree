<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyTreeResource;
use App\Models\FamilyTree;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class FamilyTreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $familyTrees = auth()->user()
            ->familyTrees()
            ->with('headPerson')
            ->get();

        return FamilyTreeResource::collection($familyTrees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return FamilyTreeResource|\Illuminate\Http\Response|void
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'personGender' => 'required|in:male,female',
            'personName' => 'required',
            'personSurname' => 'required',
            'personBirthdate' => 'required|date_format:Y-m-d',
        ]);

        $familyTree = null;

        try {
            DB::beginTransaction();

            $person = Person::create([
                'owner_id' => auth()->user()->id,
                'gender' => $request->input('personGender'),
                'name' => $request->input('personName'),
                'surname' => $request->input('personSurname'),
                'birthdate' => $request->input('personBirthdate'),
            ]);

            $familyTree = FamilyTree::create([
                'owner_id' => auth()->user()->id,
                'head_person_id' => $person->id,
                'name' => $request->input('name'),
            ]);

            $familyTree->load('headPerson');
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            abort(422, 'Something goes wrong when trying to create family tree.');
        }

        DB::commit();

        return new FamilyTreeResource($familyTree);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return FamilyTreeResource
     */
    public function show($id)
    {
        $familyTree = FamilyTree::where('owner_id', auth()->user()->id)
            ->with('headPerson')
            ->findOrFail($id);

        return new FamilyTreeResource($familyTree);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FamilyTree::where([
            'id' => $id,
            'owner_id' => auth()->user()->id,
        ])->delete();

        return response()->noContent();
    }
}
