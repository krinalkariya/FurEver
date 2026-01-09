<?php

class PagesController
{
    // Render with layout chosen by current role; falls back to user layout.
    private function renderShared(string $view, array $data = []): void
    {
        extract($data);

        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();

        // choose layout by session role (user/ngo). fallback: user_base
        $sessUser = Session::get('user');
        $sessNgo  = Session::get('ngo');

        if ($sessUser && ($sessUser['role'] ?? '') === 'user') {
            require __DIR__ . '/../views/layout/user_base.php';
        } elseif ($sessNgo && ($sessNgo['role'] ?? '') === 'ngo') {
            require __DIR__ . '/../views/layout/ngo_base.php';
        } else {
            // if you have a public layout, swap here; else reuse user layout
            require __DIR__ . '/../views/layout/user_base.php';
        }
    }

    // GET /about
    public function about(): void
    {
        $this->renderShared('pages/about', [
            'page_title' => 'About Us · FurEver',
            'active'     => 'about', // helps navbar highlight
        ]);
    }
}
