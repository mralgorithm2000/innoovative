<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('fetch:news')->hourly();