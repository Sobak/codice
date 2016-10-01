<?php

use Codice\Label;
use Codice\Note;
use Codice\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LabelsTest extends TestCase
{
    use DatabaseMigrations;

    public function testAssigningLabels()
    {
        // Create new user - it is needed because processNewLabels() uses Auth::id()
        // Its ID will be 1
        $this->seedTestUser();

        // Log that user in
        Auth::loginUsingId(1);

        $this->seedTestLabels();

        $note = Note::create([
            'user_id' => 1,
            'content' => 'lorem ipsum dolor si amet',
            'content_raw' => 'lorem ipsum dolor si amet',
            'status'  => 0,
            'expires_at' => null
        ]);

        $labels = [
            0 => "1", // numeric ID
        ];

        $note->reTag($labels);

        $this->seeInDatabase('label_note', [
            'label_id' => 1,
            'note_id' => 1
        ]);
    }

    public function testAssigningAndCreatingLabels()
    {
        // Create new user - it is needed because processNewLabels() uses Auth::id()
        // Its ID will be 1
        $this->seedTestUser();

        // Log that user in
        Auth::loginUsingId(1);

        $this->seedTestLabels();

        $note = Note::create([
            'user_id' => 1,
            'content' => 'lorem ipsum dolor si amet',
            'content_raw' => 'lorem ipsum dolor si amet',
        ]);

        $labels = [
            0 => "1", // numeric ID
            // non-numeric (string) means that user created new label
            // (this is how select2.js behaves)
            1 => "new label",
        ];

        $note->reTag($labels);

        $this->seeInDatabase('label_note', [
            'label_id' => 1,
            'note_id' => 1
        ]);

        $this->seeInDatabase('labels', [
            'name' => 'new label'
        ]);
    }

    private function seedTestLabels()
    {
        Label::create([
            'name' => 'foo',
            'user_id' => 1
        ]);

        Label::create([
            'name' => 'bar',
            'user_id' => 1
        ]);

        Label::create([
            'name' => 'other',
            'color' => 2,
            'user_id' => 1
        ]);
    }

    private function seedTestUser()
    {
        return User::create([
            'name' => 'JohnDoe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('test'),
            'options' => [],
        ]);
    }
}
