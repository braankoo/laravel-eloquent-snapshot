<?php

namespace Braankoo\EloquentSnapshot;

use Illuminate\Database\Eloquent\Builder;

class EloquentSnapshotFilter
{
    public ?\DateTimeInterface $before = null;

    public ?\DateTimeInterface $after = null;

    public ?\DateTimeInterface $at = null;

    public bool $latest = false;

    public bool $first = false;

    public function before(\DateTimeInterface $date): self
    {
        $this->before = $date;

        return $this;
    }

    public function after(\DateTimeInterface $date): self
    {
        $this->after = $date;

        return $this;
    }

    public function first(): self
    {
        $this->first = true;
        $this->latest = false;

        return $this;
    }

    public function latest(): self
    {
        $this->latest = true;
        $this->first = false;

        return $this;
    }

    public function at(\DateTimeInterface $date): self
    {
        $this->at = $date;

        return $this;
    }

    public function apply(Builder $query): Builder
    {
        if ($this->before) {
            $query->where('created_at', '<', $this->before);
        }

        if ($this->after) {
            $query->where('created_at', '>', $this->after);
        }

        if ($this->latest) {
            $query->latest('created_at');
        } elseif ($this->first) {
            $query->oldest('created_at');
        }

        return $query;
    }
}
