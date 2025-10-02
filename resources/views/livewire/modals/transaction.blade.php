<flux:modal name="edit-profile" class="w-[90%] max-w-100" :dismissible="false">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Nova Transação</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes da nova transação abaixo.</flux:text>
        </div>

        <div class="space-y-4">
            <flux:field>
                <flux:label>Nome</flux:label>
                <flux:input wire:model.live.debounce.500ms="name" placeholder="Ex: Salário, Aluguel, etc."/>
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Valor</flux:label>
                <flux:input wire:model.live.debounce.500ms="amount" placeholder="R$ 0,00"
                    x-ref="money"
                    x-on:input="
                        let value = $refs.money.value.replace(/\D/g, '');
                        value = (value / 100).toFixed(2) + '';
                        value = value.replace('.', ',');
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        $refs.money.value = 'R$ ' + value;
                    "
                />
                <flux:error name="amount" />
            </flux:field>

            <flux:field>
                <flux:label>Tipo</flux:label>
                <flux:radio.group wire:model.live.debounce.500ms="type" variant="segmented">
                    <flux:radio label="Receita" value="Receita" class="cursor-pointer"/>
                    <flux:radio label="Despesa" value="Despesa" class="cursor-pointer"/>
                </flux:radio.group>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label>Categoria</flux:label>
                <flux:select wire:model.live.debounce.500ms="category_id" placeholder="Selecione uma categoria...">
                    @foreach($categories as $id => $name)
                        <flux:select.option value="{{$id}}">{{ $name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="category_id" />
            </flux:field>

            <flux:field>
                <flux:label badge="Opcional">Cartão de crédito</flux:label>
                <flux:select wire:model.live.debounce.500ms="card_id" placeholder="Selecione um cartão...">
                    @foreach($cards as $id => $name)
                        <flux:select.option value="{{$id}}">{{ $name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="card_id" />
            </flux:field>

            <flux:field>
                <flux:label>Data</flux:label>
                <flux:input wire:model.live.debounce.500ms="date" type="date"/>
                <flux:error name="date" />
            </flux:field>

            <flux:field>
                <flux:label badge="Opcional">Descrição</flux:label>
                <flux:textarea wire:model.live.debounce.500ms="description" placeholder="Descrição..."/>
                <flux:error name="description" />
            </flux:field>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button wire:click="save" variant="primary" class="cursor-pointer"
                 wire:target="save, name, amount, type, category_id, card_id, date, description">
                Salvar
            </flux:button>
        </div>
    </div>
</flux:modal>

