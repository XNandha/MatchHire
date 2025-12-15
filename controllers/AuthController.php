<?php
// controllers/AuthController.php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PelamarProfile.php';
require_once __DIR__ . '/../models/PerusahaanProfile.php';
require_once __DIR__ . '/../config/constants.php';

class AuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $this->view('auth/login', [
                    'error' => 'Email atau password salah.'
                ]);
                return;
            }

            Auth::login($user);

            // Redirect berdasarkan role
            if ($user['role'] === ROLE_PELAMAR) {
                redirect('jobseeker/dashboard');
            } else {
                redirect('employer/dashboard');
            }
        }

        $this->view('auth/login');
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $role = $_POST['role'];
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($password !== $confirm) {
                $this->view('auth/register', [
                    'error' => 'Konfirmasi password tidak cocok.'
                ]);
                return;
            }

            $userModel = new User();

            if ($userModel->findByEmail($email)) {
                $this->view('auth/register', [
                    'error' => 'Email sudah terdaftar.'
                ]);
                return;
            }

            // Buat user
            $userId = $userModel->create($role, $email, $password);

            // Generate profile kosong berdasarkan role
            if ($role === ROLE_PELAMAR) {
                (new PelamarProfile())->upsert($userId, []);
            } else {
                (new PerusahaanProfile())->upsert($userId, []);
            }

            // Login otomatis setelah registrasi
            $user = $userModel->findById($userId);
            Auth::login($user);

            if ($role === ROLE_PELAMAR) redirect('jobseeker/dashboard');
            redirect('employer/dashboard');
        }

        $this->view('auth/register');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('auth/login');
    }
}
