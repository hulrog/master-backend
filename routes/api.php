<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpertiseController;
use App\Http\Controllers\ExpertiseRatingController;
use App\Http\Controllers\FactController;
use App\Http\Controllers\FactVoteController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiController;

//Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Country routes
Route::get('/getAllCountries', [CountryController::class, 'getAllCountries']);

Route::middleware('auth:sanctum')->group(function () {
    
    // User routes
    Route::get('/getAllUsers', [UserController::class, 'getAllUsers']);
    Route::post('/createUser', [UserController::class, 'createUser']);
    Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
    Route::put('/updateUser/{id}', [UserController::class, 'updateUser']);
    Route::get('/getUsersByName/{name}', [UserController::class, 'getUsersByName']);

    // Area routes
    Route::get('/getAllAreas', [AreaController::class, 'getAllAreas']);
    Route::post('/createArea', [AreaController::class, 'createArea']);
    Route::get('/getAllAreasContainingLetters/{letters}', [AreaController::class, 'getAllAreasContainingLetters']);

    // Topic routes
    Route::get('/getAllTopics', [TopicController::class, 'getAllTopics']);
    Route::post('/createTopic', [TopicController::class, 'createTopic']);
    Route::get('/getAllTopicsContainingLetters/{letters}', [TopicController::class, 'getAllTopicsContainingLetters']);

    // Fact routes
    Route::get('/getAllFacts', [FactController::class, 'getAllFacts']);
    Route::post('/createFact', [FactController::class, 'createFact']);
    Route::get('/getAllFactsOfUser/{userId}', [FactController::class, 'getAllFactsOfUser']);
    Route::get('/getAllFactsOfTopic/{topicId}', [FactController::class, 'getAllFactsOfTopic']);
    Route::post('/getAllFactsThatMeetRequirements', [FactController::class, 'getAllFactsThatMeetRequirementsv2']);

    // FactVote routes
    Route::get('/getAllFactVotesOfFact/{factId}', [FactVoteController::class, 'getAllFactVotesOfFact']);
    Route::post('/createFactVote', [FactVoteController::class, 'createFactVote']);

    //AI routes
    Route::post('/testAi', [AiController::class, 'testAi']);

    // Expertise routes
    Route::get('/getAllExpertises', [ExpertiseController::class, 'getAllExpertises']);
    Route::post('/createExpertise', [ExpertiseController::class, 'createExpertise']);
    Route::delete('/deleteExpertise/{id}', [ExpertiseController::class, 'deleteExpertise']);

    // ExpertiseRating routes
    Route::get('/getAllExpertiseRatings', [ExpertiseRatingController::class, 'getAllExpertiseRatings']);
    Route::post('/createExpertiseRating', [ExpertiseRatingController::class, 'createExpertiseRating']);
    Route::get('/getAllExpertiseRatingsOfExpertise/{expertiseId}', [ExpertiseRatingController::class, 'getAllExpertiseRatingsOfExpertise']);
    Route::get('/getAllExpertisesOfUser/{userId}', [ExpertiseController::class, 'getAllExpertisesOfUser']);
});



