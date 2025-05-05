from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import pandas as pd
import time

# Opsi untuk ChromeDriver
options = Options()
options.add_argument('--no-sandbox')
options.add_argument('--disable-dev-shm-usage')
options.add_argument('--headless')  # Tambahkan ini jika tidak ingin membuka browser

# Inisialisasi driver
driver = webdriver.Chrome(options=options)
driver.get("https://bhinneka.com/jual?cari=handphone")

# Tunggu elemen utama halaman dimuat
wait = WebDriverWait(driver, 10)
products = []

try:
    # Tunggu hingga elemen produk tersedia
    items = wait.until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, 'div.o_wsale_product_information')))
    print(f"Number of items found: {len(items)}")

    for item in items:
        try:
            # Nama produk
            product_name = item.find_element(By.CSS_SELECTOR, 'h6.o_wsale_products_item_title a').text

            # Harga produk
            try:
                product_price = item.find_element(By.CSS_SELECTOR, 'span.oe_currency_value').text
            except Exception as e:
                product_price = "Price not available"

            # URL ke produk
            try:
                product_url = item.find_element(By.CSS_SELECTOR, 'h6.o_wsale_products_item_title a').get_attribute('href')
            except Exception as e:
                product_url = "URL not available"

            # Gambar produk
            try:
                image_element = item.find_element(By.CSS_SELECTOR, 'img.img.img-fluid.h-100.w-100.position-absolute')
                image_url = image_element.get_attribute('src')
            except Exception as e:
                image_url = "Image not available"

            # Debug: Cetak elemen untuk validasi
            print(f"Name: {product_name}, Price: {product_price}, URL: {product_url}, Image: {image_url}")

            # Tambahkan data ke list
            products.append((product_name, product_price, product_url, image_url))
        except Exception as e:
            print(f"Error processing item: {e}")
except Exception as e:
    print(f"Error loading items: {e}")

# Simpan ke DataFrame
df = pd.DataFrame(products, columns=['Product Name', 'Product Price', 'Product URL', 'Image URL'])

# Simpan ke file Excel
output_file = "output.xlsx"
df.to_excel(output_file, index=False)
print(f'Data disimpan di {output_file}')

# Ambil screenshot untuk debugging
# driver.save_screenshot("debug_screenshot.png")

# Tutup browser
driver.quit()
