<div id="read-card-modal" class="hidden relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div  class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="flex flex-col items-center gap-4">
            <div class="mx-auto flex shrink-0 items-center justify-center rounded-full bg-primary/10 h-25 w-25 border border-primary/20">
              <i class="fa-solid fa-id-card text-primary text-6xl"></i>
            </div>
            <div class="mt-3 text-center">
              <h3 class="text-lg font-semibold text-gray-900" id="read-card-modal-title">Aguardando Leitura do Cartão</h3>
              <div class="mt-2">
                <p class="text-gray-700" id="read-card-modal-text">Encoste o seu cartão no aparelho de leitura.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 sm:px-6 py-3 flex flex-col sm:flex-row-reverse gap-3">
          <button id="read-card-modal-cancel-button" onclick="closeReadCardModal()" type="button" class="button ghost min-h-8 sm:max-h-10 w-full">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</div>
