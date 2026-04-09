<a href="{{ route('petugas.anggota.create') }}"
   class="bg-blue-500 text-white px-4 py-2 rounded">
   + Tambah Anggota
</a>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Username</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($anggota as $a)
        <tr>
            <td>{{ $a->name }}</td>
            <td>{{ $a->username }}</td>
            <td>
                <a href="{{ route('petugas.anggota.edit', $a->id) }}">Edit</a>

                <form action="{{ route('petugas.anggota.delete', $a->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
