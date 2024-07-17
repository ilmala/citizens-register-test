<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Anagrafe Cittadini e famiglie associate</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans antialiased bg-neutral-900 text-white">
        <div class="max-w-7xl mx-auto min-h-screen flex flex-col justify-center items-center py-12 px-8">
            <div class="w-full grid lg:grid-cols-2 items-center gap-12">
                <div class="flex flex-col items-center gap-4">
                    <div class="text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5" stroke="currentColor" class="size-24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </div>

                    <h1 class="font-black text-5xl xl:text-6xl text-center">Anagrafe Cittadini <br>e famiglie associate</h1>
                    <p class="mt-8 px-4 py-1 bg-neutral-800 text-neutral-500 text-sm rounded-xl">
                        PHP Version: <span class="text-neutral-200">{{$php_version}}</span> - Laravel: <span class="text-neutral-200">{{$laravel_version}}</span>
                    </p>
                </div>
                <div>
                    <div class="flex flex-col gap-4">
                        <div class="">
                            <h2 class="font-medium text-lg text-indigo-500">Documentazione</h2>
                            <h4 class="font-semibold text-3xl">API Endpoints</h4>
                            <p class="mt-4 text-neutral-500 text-sm">Base route: <span class="font-medium text-neutral-100">/api/v1</span></p>
                        </div>
                        <div class="">
                            <h4 class="font-mono">
                                <span class="px-2 py-0.5 leading-none bg-indigo-500 text-white text-xs rounded-full">GET</span>
                                /members
                            </h4>
                            <p class="mt-1 text-neutral-500 text-sm">Elenco dei cittadini inseriti</p>
                        </div>
                        <div class="border-t border-neutral-800 w-[100px]"></div>
                        <div class="">
                            <h4 class="font-mono">
                                <span class="px-2 py-0.5 leading-none bg-indigo-500 text-white text-xs rounded-full">GET</span>
                                /families
                            </h4>
                            <p class="mt-1 text-neutral-500 text-sm">Elenco delle famiglie inserite</p>
                        </div>
                        <div class="border-t border-neutral-800 w-[100px]"></div>
                        <div class="w-full flex justify-between gap-4">
                            <div class="w-1/2">
                                <h4 class="font-mono">
                                    <span class="px-2 py-0.5 leading-none bg-orange-500 text-white text-xs rounded-full">POST</span>
                                    /responsible
                                </h4>
                                <p class="mt-1 text-neutral-500 text-sm">Permette di rendere un membro di una famiglia responsabile per la stessa.</p>
                            </div>
                            <div class="w-1/2">
                                <h5 class="font-semibold text-indigo-500 text-xs uppercase">Body params (json)</h5>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">person_id</span> : ID del Cittadino</p>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">family_id</span> : ID della Famiglia</p>
                            </div>
                        </div>
                        <div class="border-t border-neutral-800 w-[100px]"></div>
                        <div class="w-full flex justify-between gap-4">
                            <div class="w-1/2">
                                <h4 class="font-mono">
                                    <span class="px-2 py-0.5 leading-none bg-orange-500 text-white text-xs rounded-full">POST</span>
                                    /leave
                                </h4>
                                <p class="mt-1 text-neutral-500 text-sm">Permette ad un membro di una famiglia di abbandonare la stessa.</p>
                            </div>
                            <div class="w-1/2">
                                <h5 class="font-semibold text-indigo-500 text-xs uppercase">Body params (json)</h5>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">person_id</span> : ID del Cittadino</p>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">family_id</span> : ID della Famiglia</p>
                            </div>
                        </div>
                        <div class="border-t border-neutral-800 w-[100px]"></div>
                        <div class="w-full flex justify-between gap-4">
                            <div class="w-1/2">
                                <h4 class="font-mono">
                                    <span class="px-2 py-0.5 leading-none bg-orange-500 text-white text-xs rounded-full">POST</span>
                                    /move
                                </h4>
                                <p class="mt-1 text-neutral-500 text-sm">Permette di spostare un membro da una famiglia ad un altra.</p>
                            </div>
                            <div class="w-1/2">
                                <h5 class="font-semibold text-indigo-500 text-xs uppercase">Body params (json)</h5>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">person_id</span> : ID del Cittadino</p>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">from_family_id</span> : ID della Famiglia di appartenenza</p>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">to_family_id</span> : ID della Famiglia di destinazione</p>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">role</span> : Ruolo per la famiglia di destinazione</p>
                            </div>
                        </div>
                        <div class="border-t border-neutral-800 w-[100px]"></div>
                        <div class="w-full flex justify-between gap-4">
                            <div class="w-1/2">
                                <h4 class="font-mono">
                                    <span class="px-2 py-0.5 leading-none bg-orange-500 text-white text-xs rounded-full">POST</span>
                                    /families/:family/member
                                </h4>
                                <p class="text-neutral-500 text-sm">Permette di aggiungere un cittadino ad una famiglia.</p>
                            </div>
                            <div class="w-1/2">
                                <h5 class="font-semibold text-indigo-500 text-xs uppercase">Route params</h5>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">:family</span> : ID della famiglia</p>
                                <h5 class="mt-4 font-semibold text-indigo-500 text-xs uppercase">Body params (json)</h5>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">person_id</span> : ID del Cittadino</p>
                                <p class="text-neutral-500 text-xs"><span class="font-mono font-medium text-neutral-100">role</span> : Ruolo per la famiglia di destinazione</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
