<h2>Physician Search Result</h2>

<table border="1" width="100%" cellspacing="0" cellpadding="6">
<thead>
<tr>
<th>Name</th>
<th>Fathers Name</th>
<th>Registration No</th>
<th>Qualification</th>
<th>Address</th>
<th>State</th>
<th>District</th>
<th>Pincode</th>
</tr>
</thead>

<tbody>
@foreach($practitioners as $doctor)
<tr>
<td>{{ $doctor->name }}</td>
<td>{{ $doctor->fathers_name }}</td>
<td>{{ $doctor->registration_no }}</td>
<td>{{ $doctor->qualification }}</td>
<td>{{ $doctor->address }}</td>
<td>{{ $doctor->state }}</td>
<td>{{ $doctor->district }}</td>
<td>{{ $doctor->pincode }}</td>
</tr>
@endforeach
</tbody>
</table>