  {{-- Address and payment method --}}

  <section class="kbs-address-container mb-3">
      <h3>Address
          @if ($addresses->isNotEmpty())
              <i wire:click="create" class="fa-solid fa-plus ms-2"  data-toggle="tooltip" data-placement="top" title="Create New Address"></i>
          @endif
      </h3>
      @if ($addresses->isNotEmpty())
          {{-- Show address and create address button if there are addresses --}}
          @foreach ($addresses as $address)
              <div class="kbs-address-card">
                  <div class="round">
                      <input type="checkbox" name="address" value="{{$address->address_id}}" @if ($address->default_address) checked @endif
                          id="checkbox-{{ $loop->iteration }}" data-address-id="{{ $address->address_id }}" />
                      <label for="checkbox-{{ $loop->iteration }}"></label>
                  </div>

                  <div class="address">
                      <h4>Address</h4>
                      <p>{{ $address->address }}</p>
                  </div>
                  <div class="state">
                      <h4>State</h4>
                      <p>{{ $address->state }}</p>
                  </div>
                  <div class="phone-number">
                      <h4>Postal Code</h4>
                      <p>{{ $address->postal_code }}</p>
                  </div>
              </div>
          @endforeach
      @endif
      @if ($addresses->isEmpty() || $create_form_display)
          {{-- Show form if there is no address --}}
          @if ($addresses->isEmpty())
              <h4 class="address-warning">You don't have any address. Please create address for your order</h4>
          @endif
          <form wire:submit="saveaddress">
              <label for="address">Address:</label>

              <span class='text-danger'> @error('address')
                      {{ $message }}
                  @enderror
              </span>
              <input type="text" wire:model="address" id="">
              <label for="state">State:</label>
              <span class='text-danger'> @error('state')
                      {{ $message }}
                  @enderror
              </span>
              <input type="text" wire:model="state" id="">
              <label for="postal_code">Postal Code:</label>
              <span class='text-danger'> @error('postal_code')
                      {{ $message }}
                  @enderror
              </span>
              <input type="number" wire:model="postal_code">
              <label for="phone_number">Phone Number:</label>
              <span class='text-danger'> @error('phone_number')
                      {{ $message }}
                  @enderror
              </span>
              <input type="text" wire:model="phone_number" id="">
              <div class="d-flex justify-content-evenly align-items-center mt-3">
                  @if ($addresses->isNotEmpty())
                      <div class="d-flex align-items-center">
                          <div class="round">
                              <input type="checkbox" wire:model="default_address" value="1" name="default_address"
                                  id="default_address" />
                              <label class="checkbox" for="default_address"></label>
                          </div>
                          <h3 class="ms-4 mb-0">Wish to make this as default address?</h3>
                      </div>
                      <div class="d-flex align-items-center">
                          <div class="round">
                              <input type="checkbox" wire:model="default_billing_address" value="1"
                                  id="default_billing_address" />
                              <label class="checkbox" for="default_billing_address"></label>

                          </div>
                          <h3 class="ms-4 mb-0">Wish to make this as default billing
                              address?</h3>
                      </div>
                  @endif
              </div>

              <div class="d-flex justify-content-evenly align-items-center mt-3">
                  <button type="submit">Create</button>
                  @if ($addresses->isNotEmpty())
                      <button type="button" wire:click="closeform"> Close</button>
                  @endif
              </div>

          </form>
      @endif
      <section class="mt-3">
          <h3>Billing Address</h3>

          {{-- Show address and create address button if there are addresses --}}
          @foreach ($addresses as $address)
              <div class="kbs-address-card">
                  <div class="round">
                      <input type="checkbox" name="billing_address" value="{{$address->address_id}}" @if ($address->default_billing_address) checked @endif
                          id="default-billing-address-{{ $loop->iteration }}"
                          data-address-id="{{ $address->address_id }}" />
                      <label for="default-billing-address-{{ $loop->iteration }}"></label>
                  </div>

                  <div class="address">
                      <h4>Address</h4>
                      <p>{{ $address->address }}</p>
                  </div>
                  <div class="state">
                      <h4>State</h4>
                      <p>{{ $address->state }}</p>
                  </div>
                  <div class="phone-number">
                      <h4>Postal Code</h4>
                      <p>{{ $address->postal_code }}</p>
                  </div>
              </div>
          @endforeach
      </section>
  </section>

  @script
      <script>
          Livewire.on('render-component', (event) => {
              let status = event.status;
              let message = event.message;

              toast(status, message);
          });
      </script>
  @endscript
