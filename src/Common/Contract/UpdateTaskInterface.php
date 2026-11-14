<?php

namespace App\Common\Contract;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface UpdateTaskInterface
{
    public function update();
}
