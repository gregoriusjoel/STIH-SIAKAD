import re

with open('resources/views/page/mahasiswa/dashboard.blade.php', 'r') as f:
    text = f.read()

# 1. Extract Tugas Kuliah chunk
start_marker = "                {{-- Tugas Dosen Card --}}"
end_marker = "                {{-- PA Contact --}}"

start_idx = text.find(start_marker)
end_idx = text.find(end_marker)

if start_idx == -1 or end_idx == -1:
    print("Marks not found!")
    exit(1)

tugas_chunk = text[start_idx:end_idx]

# Remove it from its original place
text = text[:start_idx] + text[end_idx:]

# 2. Find where to insert (after Pengumuman Terbaru)
insert_marker = "                    @endif\n                </div>\n            </div>"
new_insert = "                    @endif\n                </div>\n\n" + tugas_chunk + "            </div>"

text = text.replace(insert_marker, new_insert)

# 3. Remove h-full from Pengumuman Terbaru
text = text.replace('p-6 flex flex-col h-full', 'p-6 flex flex-col')

# 4. Remove mt-6 from Tugas Dosen Card since it's already in space-y-6 container
text = text.replace('p-5 mt-6', 'p-6') # p-6 for consistency with Pengumuman card

with open('resources/views/page/mahasiswa/dashboard.blade.php', 'w') as f:
    f.write(text)

print("Done")
