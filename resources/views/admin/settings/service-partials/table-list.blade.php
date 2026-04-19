            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Mã dịch vụ</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Mô tả</th>
                                    <th>Thời gian (Phút)</th>
                                    <th class="text-end">Giá niêm yết</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($services as $service)
                                    <tr>
                                        <td>SVC-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}</td>

                                        <td class="fw-bold">
                                            {{ $service->name }}
                                        </td>

                                        <td>
                                            {{ $service->description }}
                                        </td>

                                        <td>
                                            {{ $service->estimated_duration }}
                                        </td>

                                        <td class="text-end fw-bold text-primary">
                                            {{ number_format($service->price) }}đ
                                        </td>

                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light border edit-service"
                                                data-bs-toggle="modal" data-bs-target="#create-service"
                                                data-id="{{ $service->id }}" data-name="{{ $service->name }}"
                                                data-icon="{{ Storage::disk()->url($service->icon) }}"
                                                data-price="{{ $service->price }}"
                                                data-estimated_duration="{{ $service->estimated_duration }}"
                                                data-description="{{ $service->description }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light border delete-service"
                                                data-id="{{ $service->id }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Chưa có dịch vụ nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
