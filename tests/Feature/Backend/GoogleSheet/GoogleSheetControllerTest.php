<?php

namespace Tests\Feature\Backend\GoogleSheet;

use App\Models\GoogleSheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ReadRolesTest.
 */
class GoogleSheetControllerTest extends TestCase
{
    use RefreshDatabase;

    public const URL = '/admin/google-sheet';
    public const URL_GOOGLE_SHEET = 'https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit#gid=0';

    public function testIndex()
    {
        $this->loginAsAdmin();

        GoogleSheet::create([
            'name' => 'Google sheet',
            'url' => 'test url',
            'associations' => ['domain' => '1'],
        ]);

        $response = $this->get(self::URL . '/');
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $this->loginAsAdmin();

        $response = $this->get(self::URL . '/create');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $this->loginAsAdmin();

        $response = $this->post(self::URL, [
            'name' => 'Google sheet',
            'url' => 'test url',
            'associations' => ['domain' => '1'],
        ]);

        $response->assertRedirect(self::URL);

        $entity = GoogleSheet::where('name', 'Google sheet')->first();
        $this->assertNotNull($entity);
    }

    public function testEdit()
    {
        $this->loginAsAdmin();

        $entity = GoogleSheet::create([
            'name' => 'Google sheet',
            'url' => 'test url',
            'associations' => ['domain' => '1'],
        ]);

        $response = $this->get(self::URL . '/' . $entity->id . '/edit');
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $this->loginAsAdmin();

        $entity = GoogleSheet::create([
            'name' => 'Google sheet',
            'url' => 'test url',
            'associations' => ['domain' => '1'],
        ]);

        $response = $this->put(self::URL . '/' . $entity->id, [
            'name' => 'Google sheet updated',
            'url' => 'test url',
            'associations' => ['domain' => '1'],
        ]);

        $response->assertRedirect(self::URL);

        $entity = GoogleSheet::where('name', 'Google sheet updated')->first();
        $this->assertNotNull($entity);
    }

    public function testDestroy()
    {
        $this->loginAsAdmin();

        $entity = GoogleSheet::create([
            'name' => 'Google sheet',
            'url' => 'test url',
            'associations' => ['domain' => '1'],
        ]);

        $response = $this->delete(self::URL . '/' . $entity->id);
        $response->assertRedirect(self::URL);

        $entity = GoogleSheet::where('name', 'Entity test')->first();
        $this->assertNull($entity);
    }

    public function testLoadHeaderGoogleSheet()
    {
        $this->loginAsAdmin();

        $response = $this->post(self::URL . '/load-header', [
            'url' => self::URL_GOOGLE_SHEET
        ]);

        $response->assertJsonStructure([
            'data' => []
        ]);
    }


    public function testImportPreview()
    {
        $this->loginAsAdmin();

        $entity = GoogleSheet::create([
            'name' => 'Google sheet',
            'url' => self::URL_GOOGLE_SHEET,
            'associations' => ['domain' => '1'],
        ]);

        $response = $this->get(self::URL . '/import/' . $entity->id);

        $response->assertStatus(200);
    }

    public function testImport()
    {
        $this->loginAsAdmin();

        $entity = GoogleSheet::create([
            'name' => 'Google sheet',
            'url' => self::URL_GOOGLE_SHEET,
            'associations' => ['domain' => '1'],
        ]);

        $response = $this->post(self::URL . '/import/' . $entity->id, [
            'rows' => [1]
        ]);

        $response->assertJsonStructure([
            'redirect',
            'message'
        ]);
    }

}
