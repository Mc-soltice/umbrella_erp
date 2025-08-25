<?php
namespace App\Http\Controllers\Users;

use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\Users\UserResource;
use App\Services\Users\UserService;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return UserResource::collection($this->service->getAll());
    }

    // public function store(UserRequest $request)
    // {
    //     $user = $this->service->create($request->validated());
    //     return new UserResource($user);
    // }

    public function show($id)
    {
        $user = $this->service->find($id);
        return $user ? new UserResource($user) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(UserRequest $request, User $user)
    {
        $user = $this->service->update($user, $request->validated());
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->service->delete($user);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
