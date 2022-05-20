<?php

namespace App\Reporter;

use App\Repository\PostRepository;

class PostReporter
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function report()
    {
        return 'Ceci est le rapport des '.$this->repository->count([]).' publications';
    }
}
