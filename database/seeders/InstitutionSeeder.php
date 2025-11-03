<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    public function run()
    {
        $institutions = [
            [
                'name' => 'Universitas Indonesia',
                'email' => 'info@ui.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEq3m2V6bQv1nq2q+Yx3Gd2x7p1YQ0u8wC\nL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3z1Y9h7q3n2v8a1yZ2Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJAOj5f6Yd1z3rMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgVUNBMB4XDTI1MTAxMDEyMDAwMFoXDTI2MTAxMDEyMDAwMFowFTETMBEG\nA1UEAwwKRmFrZSBVQ0EwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAASretvlemps\nL9Z6tqvmMdxndse6dWENOvMAi9F69ruLe2wdaekKfafRcmTkZtv7tX3LVrRW3lB3\nWdW/fPVj2Huqef2vxrXJnZAo1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUw\nAwEB/zAKBggqhkjOPQQDAgNHADBEAiA0vGZs7b0x3x3Q7qgk1YZ9GgKX9r6s8x9c\nn+Xg3u+v2gIgW0l1s2Z5z2p3p1y5a1q8V0H1b2q2Kxq3l7v0p2q9x0=\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'email' => 'info@ugm.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEhK9q3nV8bQq2s1+Yc2Gd4x7r2YQ1u9wD\nP1Yr3t5u6cC2p7Rp8q1XJk2F1b+4V8y2b1Vt4Rd2nUv2z1Z8h6p3m1v7b0yX1Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJAKb9f7Yc2z1sMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgR0dBMB4XDTI1MTAxMDEyMDAwMFoXDTI2MTAxMDEyMDAwMFowFTETMBEG\nA1UEAwwKRmFrZSBHR0EwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAASEr2pezV8b\nQq2s1+Yc2Gd4x7r2YQ1u9wDP1Yr3t5u6cC2p7Rp8q1XJk2F1b+4V8y2b1Vt4Rd2n\nUv2z1Z8h6p3m1v7b0yX1Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUwAwEB\n/zAKBggqhkjOPQQDAgNHADBEAiA9m3H2vQ3ZlY5n3v6r7t9b2m3l8n5q3k2p1s4Z\nqv3r1wAiB6p8v1s2r3n4p6q7r8s9t0u1v2w3x4y5z6a7b8c9=\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'email' => 'info@itb.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAE0m3q1v9bRr2s1+Zx3Gd5y8p2YQ2u0vAq\nL2Yr3u6u7bD2p8Sp9r2XJk3G2b+6V9z2a1Wt6Qd3nWv3y2Z9h7q3n2v9a2yZ3Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJAKn8f6Yc3z2tMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgSURNQTEwHhcNMjUxMDEwMTIwMDAwWhcNMjYxMDEwMTIwMDAwWjAVMRMw\nEQYDVQQDDApGYWtlIElUQkEwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAATTbep1\nv1tGvarNfmcdxne8q2YTZ7v1wDqL2Yr3u6u7bD2p8Sp9r2XJk3G2b+6V9z2a1Wt6\nQd3nWv3y2Z9h7q3n2v9a2yZ3Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUw\nAwEB/zAKBggqhkjOPQQDAgNHADBEAiB8p3m2n4q6r8s7t9b2m3l8n5q3k2p1s4Zq\nv3r1wAiB6p8v1s2r3n4p6q7r8s9t0u1v2w3x4y5z6a7b8c9=\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Airlangga',
                'email' => 'info@unair.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAERk3q2v8cRr3t1+Zx3Gf6y9p2ZQ3u1vBq\nK2Yr3v6v7cE2p9Rp0q1XJk4G2b+5V8y2a2Wt5Rd3nUv3z3Z8h7p3n2v8a1yZ4Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJALk7f6Yd2z1qMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgVU5BMB4XDTI1MTAxMDEyMDAwMFoXDTI2MTAxMDEyMDAwMFowFTETMBEG\nA1UEAwwKRmFrZSBVTkEwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAARk3q2v8cRr\n3t1+Zx3Gf6y9p2ZQ3u1vBqK2Yr3v6v7cE2p9Rp0q1XJk4G2b+5V8y2a2Wt5Rd3nU\nv3z3Z8h7p3n2v8a1yZ4Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUwAwEB\n/zAKBggqhkjOPQQDAgNHADBEAiB9m3H2vQ3ZlY5n3v6r7t9b2m3l8n5q3k2p1s4Z\nqv3r1wAiB6p8v1s2r3n4p6q7r8s9t0u1v2w3x4y5z6a7b8c9=\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Diponegoro',
                'email' => 'info@undip.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEq9m3v7bRr3s1+Yx3Gd2x7p1YQ0u9wCq\nK0Xr2u4t7bC1p6Qp9q0XJk5G2b+7V9y2a0Vt5Rd1nVv3z2Z9h7q3n2v8a1yZ5Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJANj6f6Yc1z2pMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgRElQMB4XDTI1MTAxMDEyMDAwMFoXDTI2MTAxMDEyMDAwMFowFTETMBEG\nA1UEAwwKRmFrZSBESVQwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAASr2bep3v7b\nRr3s1+Yx3Gd2x7p1YQ0u9wCqK0Xr2u4t7bC1p6Qp9q0XJk5G2b+7V9y2a0Vt5Rd1\nnVv3z2Z9h7q3n2v8a1yZ5Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUwAwEB\n/zAKBggqhkjOPQQDAgNHADBEAiA2p9v3s4t5u6cC2p7Rp8q1XJk2F1b+4V8y2b1V\nt4Rd2nUv2z1Z8h6p3m1v7b0yX1Q==\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Brawijaya',
                'email' => 'info@ub.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEb3m1v9cRr2s1+Zx3Gd5y8p2YQ2u0vAa\nL2Yr3u6u7bD2p8Sp9r2XJk3G2b+6V9z2a1Wt6Qd3nWv3y2Z9h7q3n2v9a2yZ6Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJAKm9f6Yd3z1rMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgQlJXMB4XDTI1MTAxMDEyMDAwMFoXDTI2MTAxMDEyMDAwMFowFTETMBEG\nA1UEAwwKRmFrZSBCUldBMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEb3m1v9cRr2s1\n+Zx3Gd5y8p2YQ2u0vAaL2Yr3u6u7bD2p8Sp9r2XJk3G2b+6V9z2a1Wt6Qd3nWv3y\n2Z9h7q3n2v9a2yZ6Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUwAwEB/zA\nKBggqhkjOPQQDAgNHADBEAiBDp9v3s4t5u6cC2p7Rp8q1XJk2F1b+4V8y2b1Vt4Rd\n2nUv2z1Z8h6p3m1v7b0yX1QIgZqv3r1w==\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Sebelas Maret',
                'email' => 'info@uns.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEq3n2v8bRr2s1+Yx3Gd2x7p1YQ0u8wCq\nL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3z1Z9h7q3n2v8a1yZ7Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJAMl5f6Yc2z1sMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgVVNFMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEq3n2v8bRr2s1+Yx3Gd2x\n7p1YQ0u8wCqL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3z1Z9h7q3n\n2v8a1yZ7Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUwAwEB/zAKBggqhkj\nOPQQDAgNHADBEAiBDp9v3s4t5u6cC2p7Rp8q1XJk2F1b+4V8y2b1Vt4Rd2nUv2z1\nZ8h6p3m1v7b0yX1Q==\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Hasanuddin',
                'email' => 'info@unhas.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEq3m2V6bQv1nq2q+Yx3Gd2x7p1YQ0u8wC\nL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3z1Y9h7q3n2v8a1yZ8Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJANk4f6Yd1z2sMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgSEFOMB4XDTI1MTAxMDEyMDAwMFoXDTI2MTAxMDEyMDAwMFowFTETMBEG\nA1UEAwwKRmFrZSBITkFOMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEq3m2V6bQv1nq\n2q+Yx3Gd2x7p1YQ0u8wCL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3\nz1Y9h7q3n2v8a1yZ8Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUwAwEB/\nzAKBggqhkjOPQQDAgNHADBEAiB9m3H2vQ3ZlY5n3v6r7t9b2m3l8n5q3k2p1s4Zq\nv3r1wAiB6p8v1s2r3n4p6q7r8s9t0u1v2w3x4y5z6a7b8c9=\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Padjadjaran',
                'email' => 'info@unpad.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEt3n2v8bRr2s1+Yx3Gd2x7p1YQ0u8wCq\nL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3z1Z9h7q3n2v8a1yZ9Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJAOy3f6Yc2z1sMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgUFBERkEwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAAS3e3a5v8bRr2s1\n+Yx3Gd2x7p1YQ0u8wCqL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3z\n1Z9h7q3n2v8a1yZ9Qq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUwAwEB/zA\nKBggqhkjOPQQDAgNHADBEAiA2p9v3s4t5u6cC2p7Rp8q1XJk2F1b+4V8y2b1Vt4Rd\n2nUv2z1Z8h6p3m1v7b0yX1QIgZqv3r1w==\n-----END CERTIFICATE-----"
            ],
            [
                'name' => 'Universitas Udayana',
                'email' => 'info@unud.ac.id',
                'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEq3m2V6bQv1nq2q+Yx3Gd2x7p1YQ0u8wC\nL0Xr2u4t7bB1p6Qp9q0XJk5G2b+7V9y1a0Vt5Qd1nVv3z1Z9h7q3n2v8a1yZ8Q==\n-----END PUBLIC KEY-----",
                'ca_cert' => "-----BEGIN CERTIFICATE-----\nMIIB8TCCAZegAwIBAgIJAMy2f6Yc2z1sMAoGCCqGSM49BAMCMBUxEzARBgNVBAMM\nCkZha2UgVURBMB4XDTI1MTAxMDEyMDAwMFoXDTI2MTAxMDEyMDAwMFowFTETMBEG\nA1UEAwwKRmFrZSBVREExWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAASretvlemps\nL9Z6tqvmMdxndse6dWENOvMAi9F69ruLe2wdaekKfafRcmTkZtv7tX3LVrRW3lB3\nWdW/fPVj2Huqef2vxrXJnZAq1MwUTAOBgNVHQ8BAf8EBAMCBaAwDAYDVR0TBAUw\nAwEB/zAKBggqhkjOPQQDAgNHADBEAiB0p9v3s4t5u6cC2p7Rp8q1XJk2F1b+4V8y\n2b1Vt4Rd2nUv2z1Z8h6p3m1v7b0yX1Q==\n-----END CERTIFICATE-----"
            ],
        ];


        foreach ($institutions as $institution) {
            Institution::create(array_merge($institution, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
