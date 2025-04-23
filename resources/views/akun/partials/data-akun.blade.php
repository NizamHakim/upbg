@props(['user'])

<div class="grid grid-cols-1 gap-y-3 md:grid-cols-3 md:gap-x-4">
  <div class="flex flex-col gap-1 md:row-span-1 md:row-start-1">
    <p class="font-medium">NIK / NIP</p>
    <p class="nik text-wrap">{{ $user->nik }}</p>
  </div>
  <div class="flex flex-col gap-1 md:row-span-1 md:row-start-2">
    <p class="font-medium">Nama Lengkap</p>
    <p class="name text-wrap">{{ $user->name }}</p>
  </div>
  <div class="flex flex-col gap-1 md:row-span-1 md:row-start-3">
    <p class="font-medium">Nama Panggilan</p>
    <p class="nickname text-wrap">{{ $user->nickname }}</p>
  </div>
  <div class="flex flex-col gap-1 md:row-span-1 md:row-start-1">
    <p class="font-medium">No. HP</p>
    <a href="{{ 'http://wa.me/62' . substr($user->phone_number, 1) }}" target="_blank" class="link phone">{{ $user->phone_number }}</a>
  </div>
  <div class="flex flex-col gap-1 md:row-span-1 md:row-start-2">
    <p class="font-medium">Email</p>
    <a href="mailto:{{ $user->email }}" class="link email break-words">{{ $user->email }}</a>
  </div>
  <div class="flex flex-col gap-1 md:row-span-3 md:row-start-1">
    <p class="font-medium">Roles</p>
    @empty($user->roles)
      <p>-</p>
    @else
      <ul class="list-inside list-disc">
        @foreach ($user->roles as $role)
          <li>{{ $role->name }}</li>
        @endforeach
      </ul>
    @endempty
  </div>
</div>
