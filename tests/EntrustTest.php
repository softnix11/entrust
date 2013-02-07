<?php

use Zizaco\Entrust\Entrust;

use Mockery as m;

class EntrustTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testEntrustCan()
    {
        // Current user
        $user = m::mock( 'User' );

        // Permission manage a as true
        $user->shouldReceive('can')
            ->with( 'manage_a' )
            ->once()
            ->andReturn( true );

        // Permission manage b as false
        $user->shouldReceive('can')
            ->with( 'manage_b' )
            ->once()
            ->andReturn( false );

        $entrust = new Entrust( $this->mockAppWithCurrentUser( $user ) );

        // Check if user 'can'
        $this->assertTrue( $entrust->can('manage_a') );
        $this->assertFalse( $entrust->can('manage_b') );
    }

    public function testEntrustHasRole()
    {
        // Current user
        $user = m::mock( 'User' );

        // Permission manage a as true
        $user->shouldReceive('hasRole')
            ->with( 'AdminA' )
            ->once()
            ->andReturn( true );

        // Permission manage b as false
        $user->shouldReceive('hasRole')
            ->with( 'AdminB' )
            ->once()
            ->andReturn( false );

        $entrust = new Entrust( $this->mockAppWithCurrentUser( $user ) );

        // Check if user 'can'
        $this->assertTrue( $entrust->hasRole('AdminA') );
        $this->assertFalse( $entrust->hasRole('AdminB') );
    }

    private function mockAppWithCurrentUser( $user )
    {
        // Mock app
        $app = array( 'auth' => m::mock( 'Auth' ) );

        // Return current user within Auth mock
        $app['auth']->shouldReceive('user')
            ->andReturn( $user );

        return $app;
    }
}
