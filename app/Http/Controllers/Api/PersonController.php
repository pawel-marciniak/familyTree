<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PersonResource
     */
    public function store(Request $request)
    {
        $personData = $request->validate([
            'parent_id' => 'required|exists:persons,id',
            'name' => 'required|max:100',
            'surname' => 'required|max:100',
            'birthdate' => 'required|date_format:Y-m-d',
        ]);

        $person = null;

        try {
            $person = new Person(array_merge(
                ['owner_id' => auth()->user()->id],
                $personData,
            ));

            $person->save();
        } catch (\Exception $e) {
            report($e);

            abort(422, 'Something went wrong while trying to add new person.');
        }

        return new PersonResource($person);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPersonSubTree($id)
    {
        $root = Person::where('owner_id', auth()->user()->id)
            ->descendantsAndSelf($id)
            ->toTree()
            ->firstOrFail();

        return response()->json($root);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
