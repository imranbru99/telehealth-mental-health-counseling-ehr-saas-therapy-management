<?php

namespace Tests\Feature;

use App\Models\ClinicalNote;
use App\Models\ClinicalNoteAccessLog;
use App\Models\ClinicalNoteVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ClinicalNoteSecurityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_clinical_note_digital_signing_and_locking(): void
    {
        $note = ClinicalNote::where('is_signed', true)->firstOrFail();

        // Assert signed note is locked
        $this->assertTrue($note->is_locked);
        $this->assertTrue($note->is_signed);
        $this->assertNotNull($note->signed_at);
        $this->assertNotNull($note->signature_hash);
    }

    public function test_clinical_note_amendment_creates_version_snapshot(): void
    {
        $therapist = User::where('email', 'practitioner@demo.mhcb.test')->firstOrFail();
        $note = ClinicalNote::firstOrFail();

        $initialVersion = $note->version;
        $amendedContent = array_merge($note->content ?? [], [
            'amendment_addendum' => 'Patient contacted practitioner following session to report no adverse reactions to thought journaling homework.',
        ]);

        // Execute amendment (signature: User $user, array $newContent, string $reason)
        $note->amend(
            $therapist,
            $amendedContent,
            'Post-session client phone follow-up addendum.'
        );

        $this->assertEquals($initialVersion + 1, $note->fresh()->version);

        // Assert version record created
        $versionRecord = ClinicalNoteVersion::where('clinical_note_id', $note->id)
            ->where('amendment_reason', 'Post-session client phone follow-up addendum.')
            ->first();

        $this->assertNotNull($versionRecord);
        $this->assertEquals('Post-session client phone follow-up addendum.', $versionRecord->amendment_reason);
        $this->assertEquals($therapist->id, $versionRecord->amended_by_id);
    }

    public function test_clinical_note_access_is_logged_for_audit_compliance(): void
    {
        $therapist = User::where('email', 'practitioner@demo.mhcb.test')->firstOrFail();
        $note = ClinicalNote::firstOrFail();

        $note->logAccess($therapist, 'view');

        $log = ClinicalNoteAccessLog::where('clinical_note_id', $note->id)
            ->where('user_id', $therapist->id)
            ->where('action', 'view')
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
    }

    public function test_receptionist_and_billing_roles_distinction(): void
    {
        $receptionist = User::where('email', 'reception@demo.mhcb.test')->firstOrFail();
        $billing = User::where('email', 'billing@demo.mhcb.test')->firstOrFail();
        $therapist = User::where('email', 'practitioner@demo.mhcb.test')->firstOrFail();

        // Receptionist has no practitioner / clinical role
        $this->assertFalse($receptionist->isPractitioner());
        $this->assertTrue($receptionist->hasRole('Receptionist'));

        // Billing has billing role
        $this->assertFalse($billing->isPractitioner());
        $this->assertTrue($billing->hasRole('Billing Manager'));

        // Therapist is clinician
        $this->assertTrue($therapist->isPractitioner());
    }
}
