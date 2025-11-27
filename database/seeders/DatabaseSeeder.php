<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LokasiRental;
use App\Models\Motor;
use App\Models\UnitMotor;
use App\Models\Penyewaan;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin SuperRent',
            'username' => 'admin',
            'email' => 'admin@superrent.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'alamat' => 'Jl. Raya Denpasar No. 123',
            'role' => 'admin',
        ]);

        // Create Locations
        $lokasi1 = LokasiRental::create([
            'nama_cabang' => 'SuperRent Denpasar',
            'alamat' => 'Jl. Sunset Road No. 88, Kuta, Badung',
            'deskripsi' => 'Cabang utama kami di pusat kota Denpasar dengan koleksi motor terlengkap.',
            'latitude' => -8.6705,
            'longitude' => 115.2126,
        ]);

        $lokasi2 = LokasiRental::create([
            'nama_cabang' => 'SuperRent Ubud',
            'alamat' => 'Jl. Raya Ubud No. 45, Gianyar',
            'deskripsi' => 'Lokasi strategis di Ubud, cocok untuk wisata alam dan budaya.',
            'latitude' => -8.5069,
            'longitude' => 115.2625,
        ]);

        $lokasi3 = LokasiRental::create([
            'nama_cabang' => 'SuperRent Sanur',
            'alamat' => 'Jl. Danau Tamblingan No. 22, Sanur',
            'deskripsi' => 'Dekat dengan pantai Sanur, ideal untuk perjalanan pesisir.',
            'latitude' => -8.6881,
            'longitude' => 115.2620,
        ]);

        // Create Karyawan for each location
        $karyawan1 = User::create([
            'name' => 'Wayan Rental',
            'username' => 'karyawan1',
            'email' => 'karyawan1@superrent.com',
            'password' => Hash::make('karyawan123'),
            'phone' => '081234567891',
            'alamat' => 'Denpasar, Bali',
            'role' => 'karyawan',
            'lokasi_rental_id' => $lokasi1->id,
        ]);

        $karyawan2 = User::create([
            'name' => 'Made Rental',
            'username' => 'karyawan2',
            'email' => 'karyawan2@superrent.com',
            'password' => Hash::make('karyawan123'),
            'phone' => '081234567892',
            'alamat' => 'Ubud, Bali',
            'role' => 'karyawan',
            'lokasi_rental_id' => $lokasi2->id,
        ]);

        // Create Users
        $user1 = User::create([
            'name' => 'Alex Traveler',
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('user123'),
            'phone' => '081234567893',
            'alamat' => 'Jakarta, Indonesia',
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Joe Jones',
            'username' => 'user2',
            'email' => 'user2@example.com',
            'password' => Hash::make('user123'),
            'phone' => '081234567894',
            'alamat' => 'Surabaya, Indonesia',
            'role' => 'user',
        ]);

        // Create Motors
        $motor1 = Motor::create([
            'nama_motor' => 'Honda Beat',
            'merk' => 'Honda',
            'tipe' => 'Matic',
            'cc' => 110,
            'deskripsi' => 'Motor matic irit dan nyaman untuk jarak dekat',
        ]);

        $motor2 = Motor::create([
            'nama_motor' => 'Yamaha NMAX',
            'merk' => 'Yamaha',
            'tipe' => 'Matic',
            'cc' => 155,
            'deskripsi' => 'Motor matic maxi dengan performa maksimal',
        ]);

        $motor3 = Motor::create([
            'nama_motor' => 'Honda Vario 160',
            'merk' => 'Honda',
            'tipe' => 'Matic',
            'cc' => 160,
            'deskripsi' => 'Motor matic sporty dan bertenaga',
        ]);

        $motor4 = Motor::create([
            'nama_motor' => 'Yamaha Aerox 155',
            'merk' => 'Yamaha',
            'tipe' => 'Matic',
            'cc' => 155,
            'deskripsi' => 'Motor sport matic dengan desain ngabers',
        ]);

        $motor5 = Motor::create([
            'nama_motor' => 'Honda PCX 160',
            'merk' => 'Honda',
            'tipe' => 'Matic',
            'cc' => 160,
            'deskripsi' => 'Motor matic premium yang nyaman',
        ]);

        // Create Units for Lokasi 1
        $unit1 = UnitMotor::create([
            'motor_id' => $motor1->id,
            'lokasi_rental_id' => $lokasi1->id,
            'plat_nomor' => 'DK 1234 AB',
            'status' => 'tersedia',
        ]);

        $unit2 = UnitMotor::create([
            'motor_id' => $motor2->id,
            'lokasi_rental_id' => $lokasi1->id,
            'plat_nomor' => 'DK 2345 CD',
            'status' => 'tersedia',
        ]);

        $unit3 = UnitMotor::create([
            'motor_id' => $motor3->id,
            'lokasi_rental_id' => $lokasi1->id,
            'plat_nomor' => 'DK 3456 EF',
            'status' => 'disewa',
        ]);

        // Create Units for Lokasi 2
        $unit4 = UnitMotor::create([
            'motor_id' => $motor4->id,
            'lokasi_rental_id' => $lokasi2->id,
            'plat_nomor' => 'DK 4567 GH',
            'status' => 'tersedia',
        ]);

        $unit5 = UnitMotor::create([
            'motor_id' => $motor5->id,
            'lokasi_rental_id' => $lokasi2->id,
            'plat_nomor' => 'DK 5678 IJ',
            'status' => 'maintenance',
        ]);

        // Create Units for Lokasi 3
        $unit6 = UnitMotor::create([
            'motor_id' => $motor1->id,
            'lokasi_rental_id' => $lokasi3->id,
            'plat_nomor' => 'DK 6789 KL',
            'status' => 'tersedia',
        ]);

        // Create Penyewaan
        $penyewaan1 = Penyewaan::create([
            'user_id' => $user1->id,
            'unit_motor_id' => $unit3->id,
            'lokasi_rental_id' => $lokasi1->id,
            'tanggal_mulai' => now()->subDays(2),
            'tanggal_selesai' => now()->addDays(3),
            'status' => 'Accept',
        ]);

        $penyewaan2 = Penyewaan::create([
            'user_id' => $user2->id,
            'unit_motor_id' => $unit1->id,
            'lokasi_rental_id' => $lokasi1->id,
            'tanggal_mulai' => now()->addDays(5),
            'tanggal_selesai' => now()->addDays(8),
            'status' => 'Pending',
        ]);

        // Create Reviews
        Review::create([
            'user_id' => $user1->id,
            'lokasi_rental_id' => $lokasi1->id,
            'kategori' => 'tempat sewa',
            'rating' => 5,
            'review' => 'Pelayanan sangat baik, motor dalam kondisi prima. Highly recommended!',
        ]);

        Review::create([
            'user_id' => $user2->id,
            'lokasi_rental_id' => $lokasi2->id,
            'kategori' => 'motor',
            'rating' => 4,
            'review' => 'Motor bagus dan terawat, harga terjangkau. Puas!',
        ]);

        Review::create([
            'user_id' => $user1->id,
            'lokasi_rental_id' => null,
            'kategori' => 'website',
            'rating' => 5,
            'review' => 'Website mudah digunakan, proses booking cepat dan simple.',
        ]);

        $this->command->info('Database selesai di-seeding');
        $this->command->info('Admin: admin / admin123');
        $this->command->info('Karyawan: karyawan1 / karyawan123');
        $this->command->info('User: user1 / user123');
    }
}