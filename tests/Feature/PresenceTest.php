<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Presence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PresenceTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un admin et un utilisateur
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'member']);
    }

    /**
     * Test: Un utilisateur peut déclarer sa présence
     */
    public function test_user_can_declare_presence()
    {
        $response = $this->actingAs($this->user)->post(route('presence.store'), [
            'date' => Carbon::now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('presences', [
            'user_id' => $this->user->id,
            'present' => true,
            'validated_by_admin' => false,
        ]);
    }

    /**
     * Test: Un utilisateur ne peut pas déclarer deux fois la même présence
     */
    public function test_user_cannot_declare_twice_same_day()
    {
        $date = Carbon::now()->toDateString();

        // Première déclaration
        $this->actingAs($this->user)->post(route('presence.store'), [
            'date' => $date,
        ]);

        // Deuxième tentative
        $response = $this->actingAs($this->user)->post(route('presence.store'), [
            'date' => $date,
        ]);

        $response->assertSessionHasErrors();
    }

    /**
     * Test: L'admin peut valider une présence
     */
    public function test_admin_can_validate_presence()
    {
        $presence = Presence::factory()->create([
            'user_id' => $this->user->id,
            'validated_by_admin' => false,
        ]);

        $response = $this->actingAs($this->admin)->patch(
            route('presence.validate', $presence),
            ['validated' => true]
        );

        $response->assertRedirect();
        $this->assertTrue($presence->fresh()->validated_by_admin);
    }

    /**
     * Test: L'admin peut modifier une présence
     */
    public function test_admin_can_update_presence()
    {
        $presence = Presence::factory()->create([
            'user_id' => $this->user->id,
            'present' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(
            route('presence.update', $presence),
            [
                'present' => false,
                'validated_by_admin' => true,
            ]
        );

        $response->assertRedirect();
        $this->assertFalse($presence->fresh()->present);
        $this->assertTrue($presence->fresh()->validated_by_admin);
    }

    /**
     * Test: Un utilisateur peut voir l'index des présences
     */
    public function test_user_can_view_presence_index()
    {
        $response = $this->actingAs($this->user)->get(route('presence.index'));

        $response->assertSuccessful();
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Presence/Index')
                ->has('presenceData')
                ->where('isAdmin', false)
        );
    }

    /**
     * Test: Un utilisateur peut voir son historique
     */
    public function test_user_can_view_own_history()
    {
        // Créer quelques présences
        Presence::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('presence.history'));

        $response->assertSuccessful();
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Presence/History')
                ->where('isAdmin', false)
        );
    }

    /**
     * Test: L'admin peut voir l'historique de tous les utilisateurs
     */
    public function test_admin_can_view_all_history()
    {
        $response = $this->actingAs($this->admin)->get(route('presence.history'));

        $response->assertSuccessful();
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Presence/History')
                ->where('isAdmin', true)
        );
    }

    /**
     * Test: L'admin peut voir le rapport mensuel
     */
    public function test_admin_can_view_monthly_report()
    {
        $response = $this->actingAs($this->admin)->get(route('presence.monthlyReport'));

        $response->assertSuccessful();
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Presence/MonthlyReport')
                ->has('report')
        );
    }

    /**
     * Test: Un utilisateur non-admin ne peut pas valider les présences
     */
    public function test_non_admin_cannot_validate_presence()
    {
        $presence = Presence::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->patch(
            route('presence.validate', $presence),
            ['validated' => true]
        );

        $response->assertForbidden();
    }

    /**
     * Test: Récupérer les présences par date (API)
     */
    public function test_get_presences_by_date()
    {
        $date = Carbon::now()->toDateString();
        Presence::factory(3)->create([
            'date' => $date,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('presence.getByDate', ['date' => $date])
        );

        $response->assertSuccessful();
        $response->assertJsonCount(3);
    }

    /**
     * Test: Recherche de présences par nom de membre
     */
    public function test_search_presence_by_member_name()
    {
        $response = $this->actingAs($this->user)->get(
            route('presence.index', ['search' => $this->user->name])
        );

        $response->assertSuccessful();
        $response->assertInertia(fn ($page) =>
            $page->where('searchQuery', $this->user->name)
        );
    }
}
