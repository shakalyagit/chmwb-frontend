<h2 style="text-align:center;margin-bottom:20px;">Physician Search Result</h2>

@foreach($practitioners as $doctor)

<table border="1" width="100%" cellspacing="0" cellpadding="8" style="margin-bottom:25px;border-collapse:collapse;">

    <tr>
        <th width="30%" align="left">Name</th>
        <td width="5%" align="center">:</td>
        <td>{{ $doctor->name ?? 'N/A' }}</td>
    </tr>

    <tr>
        <th align="left">Fathers Name</th>
        <td align="center">:</td>
        <td>{{ $doctor->fathers_name ?? 'N/A' }}</td>
    </tr>

    <tr>
        <th align="left">Registration No</th>
        <td align="center">:</td>
        <td>{{ $doctor->registration_no ?? 'N/A' }}</td>
    </tr>

    <tr>
        <th align="left">Qualification</th>
        <td align="center">:</td>
        <td>{{ $doctor->qualification ?? 'N/A' }}</td>
    </tr>

    <tr>
        <th align="left">Address</th>
        <td align="center">:</td>
        <td>{{ $doctor->address ?? 'N/A' }}</td>
    </tr>

    <tr>
        <th align="left">State</th>
        <td align="center">:</td>
        <td>{{ $doctor->state ?? 'N/A' }}</td>
    </tr>

    <tr>
        <th align="left">District</th>
        <td align="center">:</td>
        <td>{{ $doctor->district ?? 'N/A' }}</td>
    </tr>

    <tr>
        <th align="left">Pincode</th>
        <td align="center">:</td>
        <td>{{ $doctor->pincode ?? 'N/A' }}</td>
    </tr>

</table>

@endforeach