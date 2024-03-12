<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name', 'Curso NodeJS')}}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto flex flex-row">
        <!-- Lista de reproducción al lado izquierdo con acordeón -->
        <div class="video-list w-1/4 bg-gray-800 p-4 text-gray-100 overflow-y-auto">
            @foreach ($videos as $directory => $videosInDirectory)
                <div class="accordion-item mb-2">
                    <button class="accordion-button w-full text-left p-2 hover:bg-gray-700 focus:outline-none" onclick="toggleAccordion('{{ $loop->index }}')">
                        {{ $directory }}
                    </button>
                    <div id="panel-{{ $loop->index }}" class="accordion-panel hidden pl-4">
                        @foreach ($videosInDirectory as $video)
                            <li class="p-2 hover:bg-gray-700 cursor-pointer" onclick="playVideo('{{ $video }}')">
                                {{ preg_replace('/[_-]+/', ' ', last(explode('/', rawurldecode($video)))) }}
                            </li>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reproductor de video a la derecha -->
        <div class="video-player w-3/4 p-4">
            <h1 class="course-title text-xl font-bold mb-4">Curso de Node.js</h1>
            <video id="videoPlayer" controls autoplay muted ="w-full">
                <source src="https://cursonodejs.s3.amazonaws.com/1.+Introducci%C3%B3n/1.+Introducci%C3%B3n.mp4" type="video/mp4"> <!-- Asegúrate de iniciar esto con un video por defecto o dejarlo vacío -->
                Tu navegador no soporta videos HTML5.
            </video>
            {{-- <h2 id="videoTitle" class="video-title mt-2"></h2> --}}
        </div>
    </div>

    <script>
        function toggleAccordion(index) {
            const panel = document.getElementById(`panel-${index}`);
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        }

        function playVideo(source) {
            const decodedSource = decodeURIComponent(source);
            const fileName = decodedSource.split('/').pop().split('.')[0];
            const player = document.getElementById('videoPlayer');
            const titleElement = document.getElementById('videoTitle');
            player.src = source;
            titleElement.innerText = fileName.replace(/[_-]/g, ' ').replace(/\s{2,}/g, ' ');
            player.load();
            player.play();
        }
    </script>
</body>
</html>
