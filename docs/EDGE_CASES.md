# Edge Cases - Payment System

## 1. CONCURRENT APPROVAL (Race Condition)
**Scenario:** Dua keuangan mencoba approve bukti bayar yang sama secara bersamaan

**Solution:**
- Use `DB::transaction()` with `lockForUpdate()`
- Check status sebelum approve
- Throw exception jika sudah diproses

```php
// In Service
$proof = PaymentProof::where('id', $proof->id)
    ->lockForUpdate()
    ->first();

if ($proof->status !== 'UPLOADED') {
    throw new \Exception('Proof already processed');
}
```

---

## 2. PEMBULATAN CICILAN
**Scenario:** Total tagihan Rp 8.123.456 dibagi 5 cicilan

**Solution:**
- Cicilan 1-4: Rp 1.624.000 (dibulatkan ke bawah ribuan)
- Cicilan 5: Rp 1.627.456 (menyerap selisih)
- Total tetap Rp 8.123.456 (exact match)

---

## 3. MAHASISWA BAYAR CICILAN TIDAK BERURUTAN
**Scenario:** Mahasiswa coba upload bukti bayar cicilan 3 padahal cicilan 2 belum PAID

**Solution:**
```php
public function canBePaid(): bool
{
    if ($this->installment_no === 1) {
        return true;
    }
    
    $previousInstallment = Installment::where('invoice_id', $this->invoice_id)
        ->where('installment_no', $this->installment_no - 1)
        ->first();
    
    return $previousInstallment && $previousInstallment->status === 'PAID';
}
```

---

## 4. DOUBLE UPLOAD BUKTI BAYAR
**Scenario:** Mahasiswa upload bukti bayar lagi padahal status WAITING_VERIFICATION

**Solution:**
- Check installment status di controller
- Return error jika status WAITING_VERIFICATION atau PAID

---

## 5. MAHASISWA MELIHAT TAGIHAN MAHASISWA LAIN
**Scenario:** Mahasiswa A coba akses invoice milik mahasiswa B

**Solution:**
```php
// Policy
public function view(User $user, Invoice $invoice): bool
{
    if ($user->role === 'mahasiswa') {
        $student = $user->student;
        return $student && $invoice->student_id === $student->id 
            && in_array($invoice->status, ['PUBLISHED', 'IN_INSTALLMENT', 'LUNAS']);
    }
    return false;
}
```

---

## 6. APPROVE CICILAN SAAT INVOICE BUKAN PUBLISHED
**Scenario:** Keuangan approve cicilan padahal invoice statusnya DRAFT/CANCELLED

**Solution:**
```php
if ($invoice->status !== 'PUBLISHED') {
    throw new \Exception('Invoice must be PUBLISHED');
}
```

---

## 7. NOMINAL TIDAK SESUAI (PARTIAL PAYMENT)
**Scenario:** Mahasiswa bayar Rp 1.500.000 padahal cicilan Rp 1.666.000

**Solution:**
```php
if (!$invoice->allow_partial && $proof->amount_submitted !== $installment->amount) {
    throw new \Exception('Amount must match installment amount');
}
```

---

## 8. FILE UPLOAD BESAR / FORMAT SALAH
**Scenario:** Mahasiswa upload file 10MB atau format .docx

**Solution:**
```php
// FormRequest
'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
```

---

## 9. APPROVE PROOF YANG SUDAH PUNYA PAYMENT
**Scenario:** Double approve dengan mekanisme berbeda

**Solution:**
```php
if ($proof->payment()->exists()) {
    throw new \Exception('Payment already exists for this proof');
}
```

---

## 10. SEMUA CICILAN PAID TAPI INVOICE BELUM LUNAS
**Scenario:** System bug tidak update status invoice

**Solution:**
```php
// After approve payment
if ($invoice->allInstallmentsPaid()) {
    $invoice->update(['status' => 'LUNAS']);
}

// Method in Invoice model
public function allInstallmentsPaid(): bool
{
    if ($this->installments()->count() === 0) {
        return false;
    }
    return $this->installments()->where('status', '!=', 'PAID')->count() === 0;
}
```

---

## 11. REJECT PROOF LALU MAHASISWA UPLOAD ULANG
**Scenario:** Proof rejected, installment status harus reset

**Solution:**
```php
// On reject
$proof->installment->update([
    'status' => 'REJECTED_PAYMENT'
]);

// Allow re-upload
if ($installment->status === 'REJECTED_PAYMENT') {
    // Allow upload
}
```

---

## 12. TANGGAL TRANSFER DI MASA DEPAN
**Scenario:** Mahasiswa input transfer_date besok

**Solution:**
```php
'transfer_date' => 'required|date|before_or_equal:today'
```

---

## 13. PAID_DATE vs TRANSFER_DATE
**Scenario:** Confusion tentang tanggal bayar resmi

**Solution:**
- `transfer_date`: tanggal mahasiswa transfer (dari input)
- `paid_date`: tanggal approve (resmi) = `now()->toDateString()`
- Riwayat pembayaran pakai `paid_date`

---

## 14. INVOICE CANCELLED TAPI MASIH ADA CICILAN AKTIF
**Scenario:** Invoice dibatalkan tapi installment masih bisa dibayar

**Solution:**
```php
// Before allow payment
if ($invoice->status === 'CANCELLED') {
    return redirect()->back()->with('error', 'Invoice dibatalkan');
}
```

---

## 15. SOFT DELETE vs HARD DELETE
**Scenario:** Data audit log harus tetap ada

**Solution:**
- Tidak pakai soft delete untuk payment/proof/installment
- Gunakan status saja (CANCELLED, REJECTED, dll)
- Data tetap ada untuk audit
