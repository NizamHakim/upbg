@props(['user'])

<img src="{{ $user->profile_picture }}" class="h-auto w-full rounded">
<label for="photo-picker" class="btn-rounded btn-upbg-solid absolute bottom-1 right-1 flex cursor-pointer items-center justify-center md:bottom-2 md:right-2"><i class="fa-solid fa-camera"></i></label>
<input id="photo-picker" type="file" name="profile-picture" class="hidden" accept="image/*">
