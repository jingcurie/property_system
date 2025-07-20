<?php

protected function schedule(Schedule $schedule)
{
    $schedule->command('test:send-notification')->everyMinute();
}
