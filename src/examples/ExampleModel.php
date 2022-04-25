<?php

namespace xianghua_we\dbeventObserver\examples;

use xianghua_we\dbeventObserver\interfaces\SplSubject;
use xianghua_we\dbeventObserver\SplSubjectEnforcer;

class ExampleModel implements SplSubject
{
    use SplSubjectEnforcer;
}
