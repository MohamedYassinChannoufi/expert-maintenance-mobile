#!/bin/bash

# Create a small test image
echo "Creating test image..."
echo -n "" > /tmp/test_image.txt

# Test JSON payload
JSON_DATA='{
  "nom": "test_upload.jpg",
  "dateCapture": "2024-01-15",
  "intervention_id": 1,
  "img": "'$(base64 -i /tmp/test_image.txt | tr -d '\n')'"
}'

echo "Testing upload endpoint..."
curl -X POST "http://192.168.100.19/ExpertMaintenance/backend/api.php?action=upload_image" \
  -H "Content-Type: application/json" \
  -d "$JSON_DATA" \
  -v 2>&1 | tail -20
