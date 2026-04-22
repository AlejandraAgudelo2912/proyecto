</main>
</div>

<footer class="w-full mt-16 bg-white border-t">

    <div class="max-w-6xl mx-auto px-6 py-8">

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

            <div class="text-gray-700 font-semibold text-lg">
                Presta tu libro, comparte tu pasión
            </div>

            <div class="flex gap-6 text-sm text-gray-500">
                <a href="<?= BASE_URL ?>public/index.php" class="hover:text-blue-600 transition">Inicio</a>
                <a href="<?= BASE_URL ?>src/views/listadoLibros.php" class="hover:text-blue-600 transition">Libros</a>
                <a href="#" class="hover:text-blue-600 transition">Contacto</a>
            </div>

            <div class="text-gray-400 text-sm">
                © <?= date('Y') ?> Presta tu libro. Todos los derechos reservados.
            </div>

        </div>

    </div>

</footer>

</body>
</html>