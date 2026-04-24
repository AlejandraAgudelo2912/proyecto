</main>
</div>

<footer class="w-full mt-16 bg-white/80 backdrop-blur border-t border-gray-200">

    <div class="max-w-6xl mx-auto px-6 py-10">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">

            <!-- Marca -->
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Presta tu libro</h2>
                <p class="text-gray-500 text-sm">
                    Comparte historias, descubre mundos y deja de acumular libros como un dragón
                </p>
            </div>

            <!-- Links -->
            <div class="flex justify-center gap-6 text-sm text-gray-500">
                <a href="<?= BASE_URL ?>public/index.php" 
                   class="hover:text-blue-600 transition">
                   Inicio
                </a>

                <a href="<?= BASE_URL ?>src/views/listadoLibros.php" 
                   class="hover:text-blue-600 transition">
                   Libros
                </a>

                <a href="#" 
                   class="hover:text-blue-600 transition">
                   Contacto
                </a>
            </div>

            <!-- Copyright -->
            <div class="text-center md:text-right text-gray-400 text-sm">
                © <?= date('Y') ?> Presta tu libro <br>
                Hecho con 💙 y probablemente poco sueño
            </div>

        </div>

    </div>

</footer>

</body>
</html>