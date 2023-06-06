<?php

use Carbon\Carbon;
use DTApi\Helpers\TeHelper;
use PHPUnit\Framework\TestCase;

class TeHelperTest extends TestCase
{
    public function testWillExpireAtWithFutureDueTime()
    {
        // Arrange
        $dueTime = '2023-06-05 12:00:00';
        $createdAt = '2023-06-01 08:00:00';

        // Act
        $result = TeHelper::willExpireAt($dueTime, $createdAt);

        // Assert
        $this->assertEquals($dueTime, $result);
    }

    public function testWillExpireAtWithPastDueTime()
    {
        // Arrange
        $dueTime = '2023-05-31 10:00:00';
        $createdAt = '2023-06-01 08:00:00';

        // Act
        $result = TeHelper::willExpireAt($dueTime, $createdAt);

        // Assert
        $this->assertNull($result);
    }

    public function testWillExpireAtWithSameDueAndCreatedAt()
    {
        // Arrange
        $dueTime = '2023-06-01 08:00:00';
        $createdAt = '2023-06-01 08:00:00';

        // Act
        $result = TeHelper::willExpireAt($dueTime, $createdAt);

        // Assert
        $this->assertEquals($dueTime, $result);
    }

    public function testWillExpireAtWithInvalidDueTime()
    {
        // Arrange
        $dueTime = 'invalid_datetime';
        $createdAt = '2023-06-01 08:00:00';

        // Act
        $result = TeHelper::willExpireAt($dueTime, $createdAt);

        // Assert
        $this->assertNull($result);
    }

    public function testWillExpireAtWithInvalidCreatedAt()
    {
        // Arrange
        $dueTime = '2023-06-05 12:00:00';
        $createdAt = 'invalid_datetime';

        // Act
        $result = TeHelper::willExpireAt($dueTime, $createdAt);

        // Assert
        $this->assertNull($result);
    }
}
