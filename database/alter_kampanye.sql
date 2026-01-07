-- Alter table kampanye to add kategori and gambar columns
ALTER TABLE `kampanye` ADD COLUMN `kategori` VARCHAR(50) DEFAULT 'Umum' AFTER `status`;
ALTER TABLE `kampanye` ADD COLUMN `gambar` VARCHAR(255) DEFAULT 'https://images.unsplash.com/photo-1593113630400-ea4288922497?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' AFTER `kategori`;